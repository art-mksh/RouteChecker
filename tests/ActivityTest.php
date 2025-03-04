<?php

namespace ArtMksh\LaravelActive\Tests;

use ArtMksh\LaravelActive\Contracts\Activity;
use Illuminate\Http\Request;

class ActivityTest extends TestCase
{
    protected Activity $active;

    protected function setUp(): void
    {
        parent::setUp();
        $this->active = $this->app->make(Activity::class);
    }

    protected function tearDown(): void
    {
        unset($this->active);
        parent::tearDown();
    }

    /** @test */
    public function it_can_instantiate_activity(): void
    {
        static::assertInstanceOf(Activity::class, $this->active);
    }

    /** @test */
    public function it_checks_if_current_request_is_active(): void
    {
        $this->get('foo');

        static::assertTrue($this->active->is(['foo']));
        static::assertTrue($this->active->isPath(['foo']));
        static::assertFalse($this->active->isRoute(['foo']));
    }

    /** @test */
    public function it_checks_if_current_request_is_active_with_string(): void
    {
        $this->get('foo');

        static::assertTrue($this->active->is('foo'));
        static::assertTrue($this->active->isPath('foo'));
        static::assertFalse($this->active->isRoute('foo'));
    }

    /** @test */
    public function it_checks_if_current_route_is_active(): void
    {
        $this->get(route('home'));

        static::assertTrue($this->active->is(['home']));
        static::assertTrue($this->active->isRoute(['home']));
        static::assertFalse($this->active->isPath(['home']));
    }

    /** @test */
    public function it_checks_if_current_full_url_is_active(): void
    {
        $this->get($url = route('pages.index'));

        static::assertSame("{$this->baseUrl}/pages", $url);
        static::assertTrue($this->active->is($url));
        static::assertFalse($this->active->is("{$this->baseUrl}/blog"));
    }

    /** @test */
    public function it_checks_if_current_path_is_active_with_ignored_paths(): void
    {
        $expectations = [
            'foo' => false,
            'foo/qux' => false,
            'foo/bar' => true,
        ];

        foreach ($expectations as $uri => $expected) {
            $this->call('GET', $uri);
            static::assertSame($expected, $this->active->is(['foo/*', 'not:foo/qux']));
            static::assertFalse($this->active->isRoute(['foo/*']));
        }
    }

    /** @test */
    public function it_checks_if_current_route_is_active_with_ignored_routes(): void
    {
        $expectations = [
            'pages.index' => true,
            'pages.create' => true,
            'pages.show' => false,
        ];

        foreach ($expectations as $route => $expected) {
            $this->get(route($route));
            static::assertSame($expected, $this->active->is(['pages.*', 'not:pages.show']));
        }
    }

    /** @test */
    public function it_gets_active_class_when_matching_path(): void
    {
        static::assertNull($this->active->active(['blog']));
        static::assertNull($this->active->path(['blog']));

        $this->get('blog');

        static::assertSame('active', $this->active->active(['blog']));
        static::assertSame('is-active', $this->active->active(['blog'], 'is-active'));
        static::assertSame('active', $this->active->path(['blog']));
        static::assertSame('is-active', $this->active->path(['blog'], 'is-active'));
    }

    /** @test */
    public function it_gets_active_class_when_matching_route(): void
    {
        static::assertNull($this->active->active(['home']));
        static::assertNull($this->active->route(['home']));

        $this->get(route('home'));

        static::assertSame('active', $this->active->active(['home']));
        static::assertSame('is-active', $this->active->active(['home'], 'is-active'));
        static::assertSame('active', $this->active->route(['home']));
        static::assertSame('is-active', $this->active->route(['home'], 'is-active'));
    }

    /** @test */
    public function it_returns_false_when_route_or_path_is_not_active(): void
    {
        static::assertFalse($this->active->is(['404']));
        static::assertFalse($this->active->isRoute(['404']));
        static::assertFalse($this->active->isPath(['404']));
    }

    /** @test */
    public function it_falls_back_to_custom_inactive_class(): void
    {
        static::assertSame('inactive', $this->active->active('blog', 'active', 'inactive'));
        static::assertSame('inactive', $this->active->route('blog', 'active', 'inactive'));
        static::assertSame('inactive', $this->active->path('blog', 'active', 'inactive'));
    }

    /** @test */
    public function it_falls_back_to_custom_inactive_class_with_setter(): void
    {
        static::assertNull($this->active->active('blog'));
        static::assertNull($this->active->route('blog'));
        static::assertNull($this->active->path('blog'));

        $this->active->setFallbackClass('inactive');

        static::assertSame('inactive', $this->active->active('blog'));
        static::assertSame('inactive', $this->active->route('blog'));
        static::assertSame('inactive', $this->active->path('blog'));
    }

    /** @test */
    public function it_sets_request_instance(): void
    {
        $request = Request::create('current-request', 'GET');
        $this->active->setRequest($request);

        static::assertTrue($this->active->is(['current-request']));
    }
}