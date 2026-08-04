<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_guests_are_sent_to_the_login_screen(): void
    {
        $response = $this->get('/');

        $response->assertRedirectToRoute('login');
    }

    public function test_guest_session_status_is_unauthenticated(): void
    {
        $this->getJson('/auth/status')
            ->assertUnauthorized()
            ->assertJsonPath('authenticated', false);
    }
}
