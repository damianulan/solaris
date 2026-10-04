<?php

namespace Tests\Feature;

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
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

    public function test_the_application_shares_the_configured_theme_variants(): void
    {
        $version = app(HandleInertiaRequests::class)->version(request());

        $response = $this->withHeaders([
            'X-Inertia' => 'true',
            'X-Inertia-Version' => $version,
        ])->get('/');

        $response
            ->assertOk()
            ->assertJsonPath('component', 'Home')
            ->assertJsonPath('props.theme.light.primary', config('theme.light.primary'))
            ->assertJsonPath('props.theme.dark.primary', config('theme.dark.primary'))
            ->assertJsonPath('props.theme.variant', 'light')
            ->assertJsonPath('props.theme.updateUrl', route('theme.update'));
    }

    public function test_the_application_shares_session_snackbars(): void
    {
        $snackbars = [
            ['message' => 'Saved successfully.', 'color' => 'success'],
            ['message' => 'Review the remaining fields.', 'color' => 'warning'],
        ];
        $version = app(HandleInertiaRequests::class)->version(request());

        $response = $this->withSession(['snackbars' => $snackbars])
            ->withHeaders([
                'X-Inertia' => 'true',
                'X-Inertia-Version' => $version,
            ])->get('/');

        $response
            ->assertOk()
            ->assertJsonPath('props.snackbars', $snackbars);
    }

    public function test_the_application_shares_the_active_locale_translations_with_fallbacks(): void
    {
        $translationPath = sys_get_temp_dir().'/solaris-translations-'.Str::uuid();

        File::ensureDirectoryExists("{$translationPath}/en");
        File::ensureDirectoryExists("{$translationPath}/pl");
        File::put("{$translationPath}/en/frontend.php", '<?php return '.var_export([
            'fallback' => 'Fallback message',
            'welcome' => 'Welcome',
        ], true).';');
        File::put("{$translationPath}/pl/frontend.php", '<?php return '.var_export([
            'welcome' => 'Witaj',
        ], true).';');
        File::put("{$translationPath}/en.json", json_encode([
            'Sign out' => 'Sign out',
        ], JSON_THROW_ON_ERROR));
        File::put("{$translationPath}/pl.json", json_encode([
            'Sign out' => 'Wyloguj',
        ], JSON_THROW_ON_ERROR));

        try {
            $loader = app('translation.loader');
            $this->assertInstanceOf(FileLoader::class, $loader);
            $loader->addPath($translationPath);

            app()->setLocale('pl');
            app(Translator::class)->setFallback('en');

            $version = app(HandleInertiaRequests::class)->version(request());

            $response = $this->withHeaders([
                'X-Inertia' => 'true',
                'X-Inertia-Version' => $version,
            ])->get('/');

            $response
                ->assertOk()
                ->assertJsonPath('props.localization.locale', 'pl')
                ->assertJsonPath('props.localization.messages.frontend.welcome', 'Witaj')
                ->assertJsonPath('props.localization.messages.frontend.fallback', 'Fallback message')
                ->assertJsonPath('props.localization.messages.Sign out', 'Wyloguj');

            $onceProps = $response->json('onceProps');

            $this->assertIsArray($onceProps);
            $this->assertArrayHasKey('localization.pl', $onceProps);

            $localizationOnceProp = $onceProps['localization.pl'];

            $this->assertIsArray($localizationOnceProp);
            $this->assertSame('localization', $localizationOnceProp['prop'] ?? null);

            $this->withHeaders([
                'X-Inertia' => 'true',
                'X-Inertia-Version' => $version,
                'X-Inertia-Except-Once-Props' => 'localization.pl',
            ])->get('/')
                ->assertOk()
                ->assertJsonMissingPath('props.localization');
        } finally {
            File::deleteDirectory($translationPath);
        }
    }
}
