<?php

namespace CredPal\CPCash\Facades;

use Illuminate\Support\Facades\Facade;

class CPCash extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'cpcash';
    }
}
