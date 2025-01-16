<?php

namespace App\Helpers;

use App\Models\City;

class CitySlug
{

    public static function getSlug(): string
    {
        //получаем первый сегмент маршрута
        $slug = request()->segment(1, '');
        if ($slug) {
            //проверяем является ли он городом из бд
            $city = City::query()->where('slug', '=', $slug)->first();
            if ($city) {
                return $slug;
            }
        }
        return '';
    }

}
