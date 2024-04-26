<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AirportController extends Controller
{
    public function search(Request $request)
    {
        $airports = Airport::search($request);
        return Inertia::render('Airports', [
            'airports' => $airports
        ]);
    }


}
