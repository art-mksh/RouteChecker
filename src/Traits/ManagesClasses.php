<?php

namespace Arcanedev\LaravelActive\Traits;

trait ManagesClasses
{
    protected string $activeClass;
    protected ?string $fallbackClass;

    public function getActiveClass(): string
    {
        return $this->activeClass;
    }

    public function setActiveClass(string $class): static
    {
        $this->activeClass = $class;
        return $this;
    }

    public function getFallbackClass(): ?string
    {
        return $this->fallbackClass;
    }

    public function setFallbackClass(?string $class): static
    {
        $this->fallbackClass = $class;
        return $this;
    }

    protected function getCssClass(bool $isActive, ?string $class = null, ?string $fallback = null): ?string
    {
        return $isActive ? ($class ?? $this->getActiveClass()) : $fallback ?? $this->getFallbackClass();
    }
}