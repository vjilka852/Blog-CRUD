<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Define authentication guards for your app. Since you only have
    | "members", we’ll keep just one guard using the Member model.
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'token',
            'provider' => 'members',
            'hash' => false,
        ],
       
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | User providers define how users are retrieved from storage.
    | Here, we point to the Member model.
    |
    */

    'providers' => [
        'members' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | Password reset configuration for members.
    |
    */

    'passwords' => [
        'members' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Time (in seconds) before password confirmation expires.
    |
    */

    'password_timeout' => 10800,

    'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
    'member' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
],


];
