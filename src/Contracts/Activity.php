<?php

namespace ArtMksh\LaravelActive\Contracts;

interface Activity
{
    public function active($routes, ?string $class = null, ?string $fallback = null): ?string;

    public function route($routes, ?string $class = null, ?string $fallback = null): ?string;

    public function path($routes, ?string $class = null, ?string $fallback = null): ?string;

    public function is($routes): bool;

    public function isRoute($routes): bool;

    public function isPath($routes): bool;
}