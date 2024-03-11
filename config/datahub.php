<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    "url" => env("DATAHUB_URL"),
    "client_id" => env("DATAHUB_CLIENT_ID"),
    "client_secret" => env("DATAHUB_CLIENT_SECRET"),

];
