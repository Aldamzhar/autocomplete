<?php

namespace Database\Seeders;

use App\Models\Airport;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $json = file_get_contents(database_path('seeders/airports.json'));
        $airports = json_decode($json, true);

        foreach ($airports as $iataCode => $data) {
            Airport::insert([
                'iata_code' => $iataCode,
                'city_name_en' => $data['cityName']["en"],
                'city_name_ru' => $data['cityName']["ru"],
                'airport_name_en' => isset($data['airportName']) ? $data['airportName']["en"] : "" ,
                'airport_name_ru' => isset($data['airportName']) ? $data['airportName']["ru"] : "" ,
                'country' => $data['country'] ?? "",
                'latitude' => $data['lat'] ?? null,
                'longitude' => $data['lng'] ?? null,
                'timezone' => $data['timezone'] ?? "",
            ]);
        }
    }
}
