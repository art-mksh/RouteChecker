<?php

namespace ArtMksh\LaravelActive\Tests;

use Illuminate\Contracts\Routing\Registrar;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            \ArtMksh\LaravelActive\ActivityServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        parent::getEnvironmentSetUp($app);
        $this->setUpRoutes($app['router']);
    }

    private function setUpRoutes(Registrar $router): void
    {
        $this->defineHomeRoute($router);
        $this->definePagesRoutes($router);
    }

    private function defineHomeRoute(Registrar $router): void
    {
        $router->get('/', function () {
            return 'Homepage';
        })->name('home');
    }

    private function definePagesRoutes(Registrar $router): void
    {
        $router->get('/pages', function () {
            return 'Page: index';
        })->name('pages.index');

        $router->get('/pages/create', function () {
            return 'Page: create';
        })->name('pages.create');

        $router->get('/pages/show', function () {
            return 'Page: show';
        })->name('pages.show');
    }
}