<?php

namespace Backstage\Tools\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Backstage\Tools\Tools
 */
class Tools extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Backstage\Tools\Tools::class;
    }
}
