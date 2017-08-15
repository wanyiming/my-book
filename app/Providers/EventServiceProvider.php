<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'SocialiteProviders\Manager\SocialiteWasCalled' => [
            'SocialiteProviders\Qq\QqExtendSocialite@handle',
        ],
    ];
}
