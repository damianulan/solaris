<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

final class LocaleMiddleware
{
    public const string HEADER_NAME = 'X-Locale';

    public const string SESSION_KEY = 'locale';

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $availableLocales = self::availableLocales();
        $user = $request->user();
        $requestedLocale = $request->header(self::HEADER_NAME);

        if (is_string($requestedLocale) && in_array($requestedLocale, $availableLocales, true)) {
            $request->session()->put(self::SESSION_KEY, $requestedLocale);

            if ($user instanceof User && $user->locale !== $requestedLocale) {
                $user->updateQuietly(['locale' => $requestedLocale]);
            }

            App::setLocale($requestedLocale);

            return $next($request);
        }

        $storedLocale = $user instanceof User
            ? $user->locale
            : $request->session()->get(self::SESSION_KEY);
        $locale = is_string($storedLocale) && in_array($storedLocale, $availableLocales, true)
            ? $storedLocale
            : config('app.locale', 'en');

        App::setLocale(is_string($locale) ? $locale : 'en');

        return $next($request);
    }

    /** @return array<int, string> */
    public static function availableLocales(): array
    {
        $locales = config('app.available_locales', []);

        if (! is_array($locales)) {
            return [];
        }

        return array_values(array_filter(
            $locales,
            static fn (mixed $locale): bool => is_string($locale) && '' !== $locale,
        ));
    }
}
