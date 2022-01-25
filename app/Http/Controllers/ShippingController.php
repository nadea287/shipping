<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function create(Request $request)
    {
//        $distance = google_distance($request->get('from_address'), $request->get('to_address'));

        $from_address = str_replace(' ', '', $request->get('from_address'));
        $to_address = str_replace(' ', '', $request->get('to_address'));
        $distance = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?origins='.$from_address.'&destinations='.$to_address.'&key=AIzaSyDxczA6T86uhMRV4W30sLOwZd78-2Hmodw');
        $dist = json_decode($distance);
        $distance_result = $dist->rows[0]->elements[0]->distance->text;
        $clean_dist = preg_replace('/[^0-9.]+/', '', $distance_result);


        dd($clean_dist);
    }
}
