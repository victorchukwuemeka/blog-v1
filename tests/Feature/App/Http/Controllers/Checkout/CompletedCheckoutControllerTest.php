<?php

use Laravel\Cashier\Cashier;

use function Pest\Laravel\get;

it('renders a page when the checkout is completed', function () {
    $session = Cashier::stripe()->checkout->sessions->create([
        'mode' => 'payment',
        'line_items' => [[
            'price' => config('products.sponsored_article'),
            'quantity' => 1,
        ]],
        'billing_address_collection' => 'required',
        'tax_id_collection' => ['enabled' => true],
        'automatic_tax' => ['enabled' => true],
        'invoice_creation' => ['enabled' => true],
        'customer_creation' => 'always',
        'success_url' => url('/foo'),
        'cancel_url' => url('/bar'),
    ]);

    get(route('checkout.completed', [
        'session_id' => $session->id,
    ]))
        ->assertOk();
});
