<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

final class ProfileTest extends TestCase
{
    public function test_the_profile_edit_page_is_rendered_with_form_data(): void
    {
        $version = app(HandleInertiaRequests::class)->version(request());

        $this->withHeaders([
            'X-Inertia' => 'true',
            'X-Inertia-Version' => $version,
        ])->get(route('profile.edit'))
            ->assertOk()
            ->assertJsonPath('component', 'Profile/Edit')
            ->assertJsonPath('props.profile.name', 'Taylor Otwell')
            ->assertJsonPath('props.profile.birthDate', '1985-09-12')
            ->assertJsonPath('props.profile.profileCompletion', 75)
            ->assertJsonPath('props.profile.timezone', 'Europe/Warsaw')
            ->assertJsonPath('props.updateUrl', route('profile.update'));
    }

    public function test_valid_profile_data_is_accepted_without_persistence(): void
    {
        $this->from(route('profile.edit'))
            ->post(route('profile.update'), [
                ...$this->validProfileData(),
                '_method' => 'put',
            ])
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHasNoErrors();
    }

    public function test_invalid_profile_data_is_returned_with_field_errors(): void
    {
        $this->from(route('profile.edit'))
            ->put(route('profile.update'), [
                'name' => '',
                'email' => 'not-an-email',
                'bio' => str_repeat('a', 501),
                'birth_date' => 'not-a-date',
                'years_of_experience' => 81,
                'timezone' => 'The Moon',
                'preferred_contact' => 'carrier-pigeon',
                'newsletter' => 'sometimes',
                'public_profile' => 'sometimes',
                'profile_completion' => 101,
                'resume' => 'not-a-file',
            ])
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHasErrors([
                'name' => 'Pole name jest wymagane.',
                'email' => 'Pole email musi być prawidłowym adresem e-mail.',
                'bio' => 'Pole bio nie może mieć więcej niż 500 znaków.',
                'birth_date' => 'Pole birth date musi mieć format Y-m-d.',
                'years_of_experience' => 'Pole years of experience musi mieć wartość od 0 do 80.',
                'timezone' => 'Wybrana wartość pola timezone jest nieprawidłowa.',
                'preferred_contact' => 'Wybrana wartość pola preferred contact jest nieprawidłowa.',
                'newsletter' => 'Pole newsletter musi mieć wartość prawda albo fałsz.',
                'public_profile' => 'Pole public profile musi mieć wartość prawda albo fałsz.',
                'profile_completion' => 'Pole profile completion musi mieć wartość od 0 do 100.',
                'resume' => 'Pole resume musi być plikiem.',
            ]);
    }

    /** @return array<string, bool|int|string|UploadedFile> */
    private function validProfileData(): array
    {
        return [
            'name' => 'Ada Lovelace',
            'email' => 'ada@example.com',
            'bio' => 'Computing pioneer.',
            'birth_date' => '1815-12-10',
            'years_of_experience' => 20,
            'timezone' => 'Europe/London',
            'preferred_contact' => 'email',
            'newsletter' => false,
            'public_profile' => true,
            'profile_completion' => 90,
            'resume' => UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf'),
        ];
    }
}
