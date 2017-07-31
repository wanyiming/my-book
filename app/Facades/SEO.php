<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class SEO
 *
 * @method setTitle(string $title)
 *
 * @package App\Facades
 * @author dch
 */
class SEO extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'seo';
    }
}
