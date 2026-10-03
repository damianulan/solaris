<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Nexus\Blueprints\Nav\NavigationItem;
use Nexus\Facades\Nav\Sidebar;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Debugbar', \Fruitcake\LaravelDebugbar\Facades\Debugbar::class);
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
