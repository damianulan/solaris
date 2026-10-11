<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\Controllers\AuthController;
use Fruitcake\LaravelDebugbar\Facades\Debugbar;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Nexus\Blueprints\Nav\NavigationItem;
use Nexus\Facades\Nav\Sidebar;
use Nexus\Http\Controllers\AuthController as NexusAuthController;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('Debugbar', Debugbar::class);

        $this->app->bind(NexusAuthController::class, AuthController::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sidebar::add(
            NavigationItem::make(__('frontend.navigation.home'), 'home', 'bi-house')->setPriority(1000)
        );
    }
}
