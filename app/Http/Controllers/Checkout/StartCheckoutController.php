<?php

namespace App\Http\Controllers\Checkout;

use Laravel\Cashier\Cashier;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class StartCheckoutController extends Controller
{
    public function __invoke(string $slug) : RedirectResponse
    {
        $priceId = config("products.$slug");

        if (empty($priceId)) {
            abort(404);
        }

        $session = Cashier::stripe()->checkout->sessions->create([
            'mode' => 'payment',
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
            'billing_address_collection' => 'required',
            'tax_id_collection' => ['enabled' => true],
            'automatic_tax' => ['enabled' => true],
            'invoice_creation' => ['enabled' => true],
            'customer_creation' => 'always',
            'success_url' => route('checkout.completed') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => back()->getTargetUrl(),
        ]);

        return redirect($session->url);
    }
}
