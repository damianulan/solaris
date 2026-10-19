<?php

namespace Tests\Feature\Http\Resources;

use App\Http\Resources\AuthResource;
use App\Models\User;
use Tests\TestCase;

class AuthResourceTest extends TestCase
{
    public function test_it_exposes_profile_and_logout_urls_for_the_user_menu(): void
    {
        $user = User::factory()->make([
            'avatar' => null,
        ]);

        $resource = AuthResource::make($user)->resolve(request());

        $this->assertSame(route('profile.edit'), $resource['profileUrl']);
        $this->assertSame(route('logout'), $resource['logoutUrl']);
    }
}
