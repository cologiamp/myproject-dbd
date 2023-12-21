<?php
namespace App\Services;
class BreadcrumbsService
{
    public static function loadBreadcrumbs():array
    {
        return [
            [
                'title' => 'Hardcoded',
                'link' => '#',
                'is_active' => false
            ],
            [
                'title' => 'As Required',
                'link' => '#',
                'is_active' => false
            ],
            [
                'title' => 'In The',
                'link' => '#',
                'is_active' => false
            ], [
                'title' => 'Designs',
                'link' => '#',
                'is_active' => true
            ],
        ];
    }
}
