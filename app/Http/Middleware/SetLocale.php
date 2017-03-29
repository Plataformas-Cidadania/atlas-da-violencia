<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $rota = Route::getCurrentRoute()->getPath();
        //Log::info($rota);
        //Log::info('1: '.App::getLocale());
        if($rota=='lang/{locale}'){
            $locale = $request->route('locale');
            $response = $next($request);
            $cookie = cookie()->forever('locale', $locale);
            $response = $response->withCookie($cookie);
            App::setLocale($locale);
            //Log::info('2: '.App::getLocale());
            return $response;
        }

        if (request()->cookie('locale')){
            $locale = request()->cookie('locale');
            App::setLocale($locale);
            return $next($request);
        }


        return $next($request);
    }
}
