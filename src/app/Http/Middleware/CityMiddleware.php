<?php

namespace App\Http\Middleware;

use App\Models\City;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class CityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //получаем префикс. приходит он со / потому и ltrim
        //т.е. раб так префикс для маршрута формируется в CitySlug классе. Здесь мы окажемся, только если маршрут будет верен URL запросу.
        //либо / or /about or /news. Либо тоже самое но с префиксом города.
        $city = ltrim(\request()->route()->getPrefix(), '/');
        //uri от префикса отличается тем, что префикс формируется в cityslug и может быть пустой строкой а uri это то что за доменом следует
        $uri = $request->path();
    //    dd($city, $uri);
        if (!$city && session('city')) {
            return redirect('/' . session('city.slug') . "/$uri", 301);
        }

        //можем запретить переходить на about and news без префикса города перед ними
        // if (!$city && Route::currentRouteName() != 'index') {
        //     abort(404);
        // }

        if ($city) {
            $city_data = City::query()->where('slug', '=', $city)->firstOrFail();
            session(['city' => $city_data]);
        }

        return $next($request);
    }
}
