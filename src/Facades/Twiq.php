<?php

namespace Twiq\Facades;

use Illuminate\Support\Facades\Facade;

class Twiq extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'twiq';
    }
}