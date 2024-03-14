<?php

namespace App\Http\Middleware;

use App\Models\Client;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class IsNotClientTwo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->client != null)
        {
            if(DB::table('clients')->select('c2_id')->pluck('c2_id')->contains($request->client->id) == false)
            {
                return $next($request);
            }
            else{
                return redirect()->route('client.dashboard',['client_id' => Client::where('c2_id',$request->client->id)->first()->id]);
            }
        }
        return $next($request);
    }
}
