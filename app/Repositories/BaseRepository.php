<?php
namespace App\Repositories;



use App\Http\Requests\CreateClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\Services\BreadcrumbsService;
use Illuminate\Database\Query\Builder;
use Request;

class BaseRepository
{
    public function loadBreadcrumbs(): array
    {
        //dd(BreadcrumbsService::loadBreadcrumbs());
        return BreadcrumbsService::loadBreadcrumbs();
    }
}
