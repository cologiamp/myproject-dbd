<?php
namespace App\Services;
use Diglactic\Breadcrumbs\Breadcrumbs;

class BreadcrumbsService
{
    public static function loadBreadcrumbs():array
    {
        //dd(Breadcrumbs::generate()->toArray());
        return Breadcrumbs::generate()->toArray();
    }
}
