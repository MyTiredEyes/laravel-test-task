<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class MainController extends Controller
{

    public function index($city = null)
    {
        //код ниже под маршруты без класса хелпера
        // if (!$city && session('city')) {
        //     return redirect()->route('index', session('city.slug'), 301);
        // }

        // if ($city) {
        //     $city_data = City::query()->where('slug', '=', $city)->firstOrFail();
        //     session(['city' => $city_data]);
        // }

        $cities = City::all();
        return view('main.index', compact('cities'));
    }

    public function about()
    {
//        dump($city);
        return view('main.about');
    }

    public function news()
    {
//        dump($city);
        return view('main.news');
    }

}
