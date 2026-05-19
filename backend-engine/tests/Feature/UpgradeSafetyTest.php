<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpgradeSafetyTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_marketplace_endpoints_do_not_server_error(): void
    {
        foreach ([
            '/settings?language=en',
            '/products?language=en&limit=1',
            '/categories?language=en&limit=1',
            '/shops?language=en&limit=1',
            '/tags?language=en&limit=1',
            '/attributes?language=en&limit=1',
            '/delivery-times?language=en&limit=1',
            '/manufacturers?language=en&limit=1',
            '/faqs?language=en&limit=1',
            '/terms-and-conditions?language=en&limit=1',
            '/flash-sale?language=en&limit=1',
            '/refund-policies?language=en&limit=1',
            '/store-notices?language=en&limit=1',
            '/popular-products?language=en&limit=1',
            '/best-selling-products?language=en&limit=1',
            '/top-manufacturers?language=en&limit=1',
            '/top-authors?language=en&limit=1',
        ] as $uri) {
            $response = $this->getJson($uri);

            $this->assertLessThan(
                500,
                $response->getStatusCode(),
                "{$uri} returned a server error: ".$response->getContent()
            );
        }
    }

    public function test_public_lookup_endpoints_do_not_server_error(): void
    {
        foreach ([
            '/check-availability?slug=example-product',
            '/near-by-shop/0/0',
        ] as $uri) {
            $response = $this->getJson($uri);

            $this->assertLessThan(
                500,
                $response->getStatusCode(),
                "{$uri} returned a server error: ".$response->getContent()
            );
        }
    }

    public function test_token_login_endpoint_does_not_server_error_for_invalid_credentials(): void
    {
        $response = $this->postJson('/token', [
            'email' => 'invalid@example.test',
            'password' => 'not-a-real-password',
        ]);

        $this->assertLessThan(500, $response->getStatusCode(), $response->getContent());
        $this->assertTrue(
            in_array($response->getStatusCode(), [200, 400, 401, 422], true),
            'Unexpected token endpoint status: '.$response->getStatusCode()
        );
    }

    public function test_try_on_upload_requires_authentication(): void
    {
        $response = $this->postJson('/zyro/try-on/upload');

        $response->assertUnauthorized();
        $response->assertJsonStructure(['message']);
    }

    public function test_try_on_task_creation_requires_authentication(): void
    {
        $response = $this->postJson('/zyro/try-on/task', [
            'photo_id' => 'example-photo',
            'product_id' => 1,
        ]);

        $response->assertUnauthorized();
        $response->assertJsonStructure(['message']);
    }

    public function test_payment_webhook_routes_fail_closed_without_server_error(): void
    {
        foreach ([
            '/webhooks/stripe',
            '/webhooks/paypal',
            '/webhooks/razorpay',
            '/webhooks/flutterwave',
        ] as $uri) {
            $response = $this->postJson($uri);

            $response->assertForbidden();
            $response->assertJson([
                'message' => 'Payment processing is disabled for this MVP.',
            ]);
        }
    }

    public function test_payment_callback_route_fails_closed_without_server_error(): void
    {
        $response = $this->get('/callback/flutterwave');

        $response->assertForbidden();
        $this->assertStringContainsString(
            'Payment processing is disabled for this MVP.',
            $response->getContent()
        );
    }
}
