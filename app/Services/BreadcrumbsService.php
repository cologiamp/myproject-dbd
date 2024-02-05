<?php
namespace App\Services;
use Diglactic\Breadcrumbs\Breadcrumbs;

class BreadcrumbsService
{
    public static function loadBreadcrumbs():array
    {
        return Breadcrumbs::generate()->tap(function ($col) use (&$numberOfBreadcrumbs){
            $numberOfBreadcrumbs = count($col);
        })->map(function ($item, $key) use ($numberOfBreadcrumbs){
            return collect($item)->put('is_active',$key + 1 == $numberOfBreadcrumbs);
        })->toArray();
    }
}
