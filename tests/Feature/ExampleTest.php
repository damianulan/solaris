<?php

namespace Tests\Feature;

use App\Http\Middleware\HandleInertiaRequests;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_the_application_shares_the_configured_theme_colors(): void
    {
        $version = app(HandleInertiaRequests::class)->version(request());

        $response = $this->withHeaders([
            'X-Inertia' => 'true',
            'X-Inertia-Version' => $version,
        ])->get('/');

        $response
            ->assertOk()
            ->assertJsonPath('component', 'Home')
            ->assertJsonPath('props.theme.colors.primary', config('theme.colors.primary'));
    }
}
