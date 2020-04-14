<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\HotelsSearchRequest;
use App\Http\Resources\Hotel\Hotels;
use App\Models\Hotel;

class HotelController extends Controller
{
    /**
     * @return Hotels
     */
    public function index(): Hotels
    {
        return new Hotels(Hotel::all());
    }
    
    /**
     * @param  HotelsSearchRequest  $request
     * @return Hotels
     */
    public function search(HotelsSearchRequest $request)
    {
        $hotels = Hotel::query();
        
        if ($request->has('name')) {
            $query = trim($request->name);
            $hotels = $hotels->where('name', 'LIKE', '%'.$query.'%');
        }
        
        if ($request->has('bedrooms')) {
            $hotels = $hotels->where('bedrooms', (int) $request->bedrooms);
        }
        
        if ($request->has('bathrooms')) {
            $hotels = $hotels->where('bathrooms', (int) $request->bathrooms);
        }
        
        if ($request->has('storeys')) {
            $hotels = $hotels->where('storeys', (int) $request->storeys);
        }
        
        if ($request->has('garages')) {
            $hotels = $hotels->where('garages', (int) $request->garages);
        }
        
        $price = json_decode($request->price);

        if ($request->has('price')) {
            $hotels = $hotels->where('price', '>=', $price->start);
        }
        
        if ($request->has('end_price')) {
            $hotels = $hotels->where('price', '<=', $price->end);
        }
    
        return new Hotels($hotels->get());
    }
}
