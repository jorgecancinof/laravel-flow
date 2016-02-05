<?php

namespace CokeCancino\LaravelFlow\Facades;

use Illuminate\Support\Facades\Facade;

class Flow extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \CokeCancino\LaravelFlow\Flow::class;
    }
}
