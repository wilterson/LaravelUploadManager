<?php

namespace WiltersonGarcia\LaravelUploadManager\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelUploadManager extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laraveluploadmanager';
    }
}
