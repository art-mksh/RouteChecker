<?php

namespace ArtMksh\LaravelActive\Traits;

use Illuminate\Http\Request;

trait ManagesRequests
{
    protected ?Request $request;

    public function getRequest(): Request
    {
        return $this->request ?: app('request');
    }

    public function setRequest(Request $request): static
    {
        $this->request = $request;
        return $this;
    }
}