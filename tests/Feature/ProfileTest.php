<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Http\Middleware\HandleInertiaRequests;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_authenticated_user_can_view_their_profile_data(): void
    {
        $user = User::factory()->create([
            'firstname' => 'Ada',
            'lastname' => 'Lovelace',
            'middlename' => 'Byron',
            'username' => 'ada',
            'email' => 'ada@example.com',
            'lang' => 'en',
            'timezone' => 'Europe/London',
            'bio' => 'Computing pioneer.',
        ]);

        $version = app(HandleInertiaRequests::class)->version(request());

        $this->actingAs($user)
            ->withHeaders([
                'X-Inertia' => 'true',
                'X-Inertia-Version' => $version,
            ])->get(route('profile.edit'))
            ->assertOk()
            ->assertJsonPath('component', 'Profile/Edit')
            ->assertJsonPath('props.profile.firstname', 'Ada')
            ->assertJsonPath('props.profile.lastname', 'Lovelace')
            ->assertJsonPath('props.profile.middlename', 'Byron')
            ->assertJsonPath('props.profile.username', 'ada')
            ->assertJsonPath('props.profile.email', 'ada@example.com')
            ->assertJsonPath('props.profile.lang', 'en')
            ->assertJsonPath('props.profile.timezone', 'Europe/London')
            ->assertJsonPath('props.profile.bio', 'Computing pioneer.')
            ->assertJsonPath('props.filepondUrl', route('filepond-process'))
            ->assertJsonPath('props.updateUrl', route('profile.update'));
    }

    public function test_a_guest_cannot_view_or_update_a_profile(): void
    {
        $this->get(route('profile.edit'))->assertRedirect(route('login'));
        $this->put(route('profile.update'), $this->validProfileData())->assertRedirect(route('login'));
    }

    public function test_valid_profile_data_is_persisted(): void
    {
        $user = User::factory()->create([
            'email' => 'old@example.com',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->from(route('profile.edit'))
            ->put(route('profile.update'), $this->validProfileData())
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHasNoErrors();

        $user->refresh();

        self::assertSame('Ada', $user->firstname);
        self::assertSame('Lovelace', $user->lastname);
        self::assertNull($user->middlename);
        self::assertSame('ada-lovelace', $user->username);
        self::assertSame('ada@example.com', $user->email);
        self::assertSame('Computing pioneer.', $user->bio);
        self::assertSame('en', $user->lang);
        self::assertSame('Europe/London', $user->timezone);
        self::assertNull($user->email_verified_at);
    }

    public function test_an_avatar_is_moved_from_filepond_storage_and_persisted(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $user = User::factory()->create();

        $uploadResponse = $this->actingAs($user)->post(route('filepond-process'), [
            'avatar' => UploadedFile::fake()->image('avatar.png')->size(512),
        ]);

        $uploadResponse->assertOk();

        $this->actingAs($user)
            ->put(route('profile.update'), [
                ...$this->validProfileData(),
                'avatar' => $uploadResponse->getContent(),
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $user->refresh();

        self::assertNotNull($user->avatar);
        Storage::disk('public')->assertExists($user->avatar);
    }

    public function test_filepond_rejects_non_jpg_and_png_images(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('filepond-process'), [
                'avatar' => UploadedFile::fake()->image('avatar.gif'),
            ])
            ->assertUnprocessable();
    }

    public function test_filepond_rejects_avatars_larger_than_two_megabytes(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('filepond-process'), [
                'avatar' => UploadedFile::fake()->image('avatar.png')->size(2049),
            ])
            ->assertUnprocessable();
    }

    public function test_invalid_profile_data_is_returned_with_field_errors(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $this->actingAs($user)
            ->from(route('profile.edit'))
            ->put(route('profile.update'), [
                'firstname' => '',
                'lastname' => '',
                'middlename' => str_repeat('a', 256),
                'username' => $otherUser->username,
                'email' => $otherUser->email,
                'bio' => str_repeat('a', 501),
                'lang' => 'xx',
                'timezone' => 'The Moon',
            ])
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHasErrors([
                'firstname',
                'lastname',
                'middlename',
                'username',
                'email',
                'bio',
                'lang',
                'timezone',
            ]);
    }

    /** @return array<string, null|string> */
    private function validProfileData(): array
    {
        return [
            'firstname' => 'Ada',
            'lastname' => 'Lovelace',
            'middlename' => null,
            'username' => 'ada-lovelace',
            'email' => 'ada@example.com',
            'bio' => 'Computing pioneer.',
            'lang' => 'en',
            'timezone' => 'Europe/London',
        ];
    }
}
