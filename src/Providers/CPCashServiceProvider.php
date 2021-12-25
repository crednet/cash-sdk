<?php

namespace CredPal\CPCash\Providers;

use CredPal\CPCash\Http\Middleware\CheckWalletAccount;
use CredPal\CPCash\Services\CashService;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CPCashServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('cpcash',  CashService::class);

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/cpcash.php',
            'cpcash'
        );
    }

    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerRoutes()
            ->registerConfig()
            ->registerResources()
            ->registerMiddleware()
            ->registerMigrations();
    }

    protected function registerConfig(): CPCashServiceProvider
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/cpcash.php' => config_path('cpcash.php'),
            ], 'cpcash');
        }

        return $this;
    }

    protected function registerMigrations(): CPCashServiceProvider
    {
        if ($this->app->runningInConsole() && !class_exists('CreateCpcashWalletsTable')) {
            $this->publishes([
                __DIR__ . '/../../database/migrations/create_cpcash_wallets_table.stub' => database_path('migrations/' . date('Y_m_d_His') . '_create_cpcash_wallets_table.php'),
            ], 'migrations');
        }

        return $this;
    }

    protected function registerResources(): CPCashServiceProvider
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'cpcash');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../resources/lang' => resource_path('lang/vendor/cpcash'),
            ], 'cpcash');
        }

        return $this;
    }

    protected function registerRoutes(): CPCashServiceProvider
    {
        Route::group($this->routeConfiguration(), fn () => $this->loadRoutesFrom(__DIR__ . '/../../routes/cpcash.php'));

        return $this;
    }

    protected function routeConfiguration(): array
    {
        return [
            'prefix' => config('cpcash.prefix'),
            'middleware' => config('cpcash.middleware'),
        ];
    }

    protected function registerMiddleware(): CPCashServiceProvider
    {
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('check-wallet-account', CheckWalletAccount::class);

        return $this;
    }
}
