<x-app-layout>
    <div
        class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg my-0 mx-auto">
        <form method="POST" action="{{ route('purchase.process.payment') }}" class="card-form ">
            @csrf

            <!-- Product -->
            <div class="mt-4">
                <x-input-label for="" :value="__('Product')" />

                @foreach ($products as $key => $product)
                    <x-radio-label for="{{ $product->name }}">
                        <x-radio-input {{ $key == 0 ? 'checked="checked"' : '' }} id="{{ $product->name }}" name="product"
                            required autocomplete="product" value="{{ $product->id }}" />
                        {{ "$product->name ($ $product->price)" }}
                    </x-radio-label>
                @endforeach
            </div>

            <input type="hidden" name="payment_method" class="payment-method">

            <div class="mt-4">
                <x-input-label for="card_holder_name" :value="__('Card Holder Name')" />
                <x-text-input id="card_holder_name" class="StripeElement block mt-1 w-full" type="text"
                    name="card_holder_name" :value="old('card_holder_name')" required autofocus autocomplete="card_holder_name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="col-lg-4 col-md-6">
                <div id="card-element"></div>
            </div>
            <div id="card-errors" role="alert"></div>


            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ml-4">
                    {{ __('Checkout') }}
                </x-primary-button>
            </div>
        </form>
    </div>

    @section('styles')
        <style>
            .StripeElement {
                box-sizing: border-box;
                height: 40px;
                padding: 10px 12px;
                border: 1px solid transparent;
                border-radius: 4px;
                background-color: white;
                box-shadow: 0 1px 3px 0 #e6ebf1;
                -webkit-transition: box-shadow 150ms ease;
                transition: box-shadow 150ms ease;
            }

            .StripeElement--focus {
                box-shadow: 0 1px 3px 0 #cfd7df;
            }

            .StripeElement--invalid {
                border-color: #fa755a;
            }

            .StripeElement--webkit-autofill {
                background-color: #fefde5 !important;
            }
        </style>
    @endsection

    @section('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            let stripe = Stripe("{{ env('STRIPE_KEY') }}")
            let elements = stripe.elements()
            let style = {
                base: {
                    color: '#32325d',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                }
            }
            let card = elements.create('card', {
                style: style
            })
            card.mount('#card-element')
            let paymentMethod = null
            $('.card-form').on('submit', function(e) {
                $('button.pay').attr('disabled', true)
                if (paymentMethod) {
                    return true
                }
                stripe.confirmCardSetup(
                    "{{ $intent->client_secret }}", {
                        payment_method: {
                            card: card,
                            billing_details: {
                                name: $('.card_holder_name').val()
                            }
                        }
                    }
                ).then(function(result) {
                    if (result.error) {
                        $('#card-errors').text(result.error.message)
                        $('button.pay').removeAttr('disabled')
                    } else {
                        paymentMethod = result.setupIntent.payment_method
                        $('.payment-method').val(paymentMethod)
                        $('.card-form').submit()
                    }
                })
                return false
            })
        </script>
    @endsection

</x-app-layout>
