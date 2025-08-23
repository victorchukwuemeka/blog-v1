<?php

use function Pest\Laravel\get;

it('renders the completed checkout page with invoice and totals', function () {
    $sessionId = 'cs_test_123';

    $fakeSession = json_decode(json_encode([
        'id' => $sessionId,
        'currency' => 'USD',
        'amount_subtotal' => 10000, // $100.00
        'amount_total' => 12000, // $120.00
        'total_details' => [
            'amount_tax' => 2000, // $20.00
        ],
        'line_items' => [
            'data' => [[
                'quantity' => 1,
                'amount_total' => 12000,
                'currency' => 'USD',
                'price' => [
                    'product' => [
                        'name' => 'Sponsored Article',
                        'description' => 'One sponsored article on the blog',
                    ],
                ],
            ]],
        ],
        'customer_details' => [
            'email' => 'buyer@example.com',
            'name' => 'Buyer Name',
            'address' => [
                'line1' => '123 Main St',
                'line2' => 'Apt 4',
                'postal_code' => '12345',
                'city' => 'Metropolis',
                'country' => 'US',
            ],
            'tax_ids' => [[
                'type' => 'eu_vat',
                'value' => 'EU123456789',
            ]],
        ],
        'invoice' => [
            'number' => 'INV-0001',
            'hosted_invoice_url' => 'https://example.test/invoice/INV-0001',
        ],
        'payment_intent' => [
            'latest_charge' => [
                'receipt_url' => 'https://example.test/receipt/rcpt_123',
            ],
        ],
    ]));

    cache()->put("checkout.session.$sessionId", $fakeSession, now()->addHour());

    get(route('checkout.completed', ['session_id' => $sessionId]))
        ->assertOk()
        ->assertSee('Thanks for your purchase!')
        ->assertSee('Sponsored Article')
        ->assertSee('Subtotal')
        ->assertSee('Tax')
        ->assertSee('Total')
        ->assertSee('buyer@example.com')
        ->assertSee('View invoice');
});
