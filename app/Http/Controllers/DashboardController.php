<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $purchase = Purchase::with(['product'])->where('user_id', $user->id)->first();

        return response()->view('dashboard.index', ['purchase' => $purchase, 'user' => $user]);
    }
}