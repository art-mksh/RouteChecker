<?php

namespace ArtMksh\LaravelActive\Tests;

use ArtMksh\LaravelActive\ActivityServiceProvider;
use ArtMksh\LaravelActive\Contracts\Activity;
use Illuminate\Support\ServiceProvider;

class ServiceProviderTest extends TestCase
{
    protected ActivityServiceProvider $provider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->provider = $this->app->getProvider(ActivityServiceProvider::class);
    }

    protected function tearDown(): void
    {
        unset($this->provider);
        parent::tearDown();
    }

    /** @test */
    public function can_instantiate(): void
    {
        static::assertInstanceOf(ActivityServiceProvider::class, $this->provider);
        static::assertInstanceOf(ServiceProvider::class, $this->provider);
        static::assertInstanceOf(\ArtMksh\Support\Providers\ServiceProvider::class, $this->provider);
        static::assertInstanceOf(\ArtMksh\Support\Providers\PackageServiceProvider::class, $this->provider);
    }

    /** @test */
    public function provides_active_contract(): void
    {
        $expected = [Activity::class];
        static::assertSame($expected, $this->provider->provides());
    }
}