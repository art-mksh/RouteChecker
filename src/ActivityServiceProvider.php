<?php

namespace ArtMksh\LaravelActive;

use ArtMksh\LaravelActive\Contracts\Activity as ActiveContract;
use ArtMksh\Support\Providers\PackageServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

class ActivityServiceProvider extends PackageServiceProvider implements DeferrableProvider
{
    protected string $package = 'active';

    public function register(): void
    {
        parent::register();

        $this->registerConfig();
        $this->registerActiveService();
    }

    protected function registerActiveService(): void
    {
        $this->singleton(ActiveContract::class, function ($app) {
            return new Activity($app['config']['active'] ?? []);
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishConfig();
        }
    }

    public function provides(): array
    {
        return [ActiveContract::class];
    }
}