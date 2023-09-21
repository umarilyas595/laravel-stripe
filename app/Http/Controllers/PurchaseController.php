<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PurchaseController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user) {
            return response()->redirectTo('/dashboard');
        }

        return response()->view('purchase.index', []);
    }

    public function process(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect('purchase/pay');
    }


    public function pay()
    {
        $user = Auth::user();
        $purchase = Purchase::where('user_id', $user->id)->first();

        if ($purchase) {
            return response()->redirectTo('dashboard');
        }

        $products = Product::get();
        $intent = auth()->user()->createSetupIntent();
        return response()->view('purchase.pay', ['intent' => $intent, 'products' => $products]);
    }

    public function processPayment(Request $request)
    {
        $user = $request->user();
        $paymentMethod = $request->input('payment_method');
        $productId = $request->input('product');

        $product = Product::where('id', $productId)->first();

        $toCharge = $product->price * 100;
        try {
            $user->createOrGetStripeCustomer();
            $user->updateDefaultPaymentMethod($paymentMethod);
            $user->charge($toCharge, $paymentMethod);
        } catch (\Exception $exception) {
            return response()->redirectTo('purchase/pay')->with('error', $exception->getMessage());
        }

        $purchase = new Purchase();
        $purchase->user_id = $user->id;
        $purchase->amount = $toCharge / 100;
        $purchase->product_id = $productId;
        $purchase->save();

        $b2bRole = Role::where('name', 'like', '%b2b%')->first();
        if ($product->system_name === 'b2b') {
            $user = User::where('id', $user->id)->first();
            $user->roles()->sync([$b2bRole->id]);
            $user->save();
        }

        $b2cRole = Role::where('name', 'like', '%b2c%')->first();
        if ($product->system_name === 'b2c') {
            $user = User::where('id', $user->id)->first();
            $user->roles()->sync([$b2cRole->id]);
            $user->save();
        }


        return response()->redirectTo('dashboard')->with('message', 'Product purchased successfully!');
    }
}