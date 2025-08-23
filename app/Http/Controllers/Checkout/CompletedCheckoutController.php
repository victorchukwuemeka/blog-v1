<?php

namespace App\Http\Controllers\Checkout;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;
use App\Http\Controllers\Controller;

class CompletedCheckoutController extends Controller
{
    public function __invoke(Request $request) : View
    {
        $session = cache()->remember(
            key: "checkout.session.$request->session_id",
            ttl: now()->addHour(),
            callback: fn () => Cashier::stripe()->checkout->sessions->retrieve(
                $request->session_id, [
                    'expand' => [
                        'customer',
                        'invoice',
                        'line_items.data.price.product',
                        'line_items',
                        'payment_intent.latest_charge',
                        'payment_intent',
                    ],
                ]
            )
        );

        return view('checkout.completed', compact('session'));
    }
}
