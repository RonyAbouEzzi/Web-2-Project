<?php

namespace Tests\Feature;

use Tests\TestCase;

class SecurityHeadersMiddlewareTest extends TestCase
{
    public function test_security_headers_are_added_on_web_routes(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('Content-Security-Policy');
        $this->assertStringContainsString(
            "frame-ancestors 'self'",
            (string) $response->headers->get('Content-Security-Policy')
        );
    }
}
