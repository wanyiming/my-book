<?php

namespace App\Providers;

use App\Libraries\Captcha\Captcha;
use Illuminate\Support\ServiceProvider;

class GetCaptchaProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish configuration files
        $this->publishes([
            __DIR__.'/../../config/captcha.php' => config_path('captcha.php')
        ], 'config');

        // HTTP routing
        if (strpos($this->app->version(), 'Lumen') !== false) {
            $this->app->get('captcha[/{config}]', 'Mews\Captcha\LumenCaptchaController@getCaptcha');
        } else {
            if ((double) $this->app->version() >= 5.2) {
                $this->app['router']->get('captcha/{config?}', 'App\Http\Controllers\Home\CaptchaController@getCaptcha')->middleware('web');
            } else {
                $this->app['router']->get('captcha/{config?}', 'App\Http\Controllers\Home\CaptchaController@getCaptcha');
            }
        }


        // Validator extensions
        $this->app['validator']->extend('captcha', function($attribute, $value, $parameters)
        {
            return captcha_check($value);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Merge configs
        /*$this->mergeConfigFrom(
            __DIR__.'/../config/captcha.php', 'captcha'
        );*/

        // Bind captcha
        $this->app->bind('captcha', function($app)
        {
            return new Captcha(
                $app['Illuminate\Filesystem\Filesystem'],
                $app['Illuminate\Config\Repository'],
                $app['Intervention\Image\ImageManager'],
                $app['Illuminate\Session\Store'],
                $app['Illuminate\Hashing\BcryptHasher'],
                $app['Illuminate\Support\Str']
            );
        });
    }
}
