<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class SearchProviderController extends Controller
{
    public function __invoke(Request $request)
    {
        return  Response::json(array_values(collect(Cache::get('io_provider_list'))->filter(function ($item) use($request){
            return str_contains(Str::lower($item),Str::lower($request->search));
        })->take(100)->map(function ($item,$key){
            return [
                'label' => $item,
                'value' => $key
            ];
        })->toArray()));
    }
}
