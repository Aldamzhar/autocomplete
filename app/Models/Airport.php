<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    public $incrementing = false;
    protected $primaryKey = 'iata_code';
    protected $keyType = 'string';
    protected $fillable = ['iata_code', 'city_name_en', 'airport_name_en', 'city_name_ru', 'airport_name_ru', 'country', 'latitude', 'longitude', 'timezone'];

    public static function search($request) {
        $searchTerm = $request->input('query');
        return Airport::query()
            ->when($searchTerm, function ($query) use ($searchTerm) {
                return $query->where('city_name_en', 'like', '%' . $searchTerm . '%')
                    ->orWhere('city_name_ru', 'like', '%' . $searchTerm . '%')
                    ->orWhere('iata_code', 'like', '%' . $searchTerm . '%');
            })
            ->get();
    }

}
