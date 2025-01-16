<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CityController extends Controller
{
    
    public function getCountriesCapital()
    {
        $data = Http::get('https://countriesnow.space/api/v0.1/countries/capital');
        dump($data->ok());
        dump($data->json()['error']);
    }

    //action для вывода городов по конкретной стране
    public function getCities()
    {
        //исп фасад http с методом retry пытаемся повторить 3 раза пост запрос с телом страны
        $cities_data = Http::retry(3, 100, throw: false)->post('https://countriesnow.space/api/v0.1/countries/cities', [
            'country' => 'spain'
        ])->json();

        dump($cities_data);

        //если данных нет то сообщ об ошибке
        if (empty($cities_data['data'])) {
            return $cities_data['msg'];
        }

        //чистим всю таблицу
        City::query()->truncate();
        //разбиваем на чанки большое кол-во городов
        $cities_chunks = array_chunk($cities_data['data'], 500);
        $ignored = 0;
        //утрамбовываем один чанк-массив в массив
        foreach ($cities_chunks as $cities) {
            $values = [];
            foreach ($cities as $city) {
                $values[] = [
                    'title' => $city,
                    'slug' => Str::slug($city),//спец хелпер формирует слаг
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
            }
            //выполняем ОДИН запрос инзерт в БД. Если есть повторяющ записи, то игнор их
            //а могли бы каждый город писать одним запросом, т.е. 16к городов =16к запросов
            $inserts = DB::table('cities')->insertOrIgnore($values);
            //подсчет игнорируемых запросов в БД
            $ignored += (count($values) - $inserts);
            
        }

        $all_cities = count($cities_data['data']);
        $inserted_cities = $all_cities - $ignored;
        return "Retrieved cities: <b>$all_cities</b> | Inserted cities: <b>$inserted_cities</b> | Ignored: <b>$ignored</b>";
    }

}
