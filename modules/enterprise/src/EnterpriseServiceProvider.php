<?php

namespace Modules\Enterprise;

use Illuminate\Support\ServiceProvider;
use Nexus\Blueprints\Nav\NavigationItem;
use Nexus\Facades\Nav\Sidebar;

/**
 * @author Damian Ułan <damian.ulan@protonmail.com>
 * @copyright 2026 damianulan
 * @license MIT
 */
class EnterpriseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/enterprise.php', 'enterprise');
        $this->app->bind('test', fn() => 'test');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/enterprise.php' => config_path('enterprise.php'),
        ], 'modularis-config');

        $this->publishes([
            __DIR__ . '/../config/enterprise.php' => config_path('enterprise.php'),
        ], 'modularis');

        $this->registerCommands();

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        Sidebar::add(NavigationItem::make('Enterprise', 'enterprise.index', 'bi-building'));
    }

    public function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            //
        }
    }
}
