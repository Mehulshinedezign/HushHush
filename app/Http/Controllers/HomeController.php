<?php

namespace App\Http\Controllers;

use App\Helpers\LocationHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function testing(Request $request)
    {
        $test = Storage::url('products\images\15_1724412712_3.jpg');
        $imageData = @file_get_contents($test);
        dd($request,$test,$imageData , 'hererer');
        return view('home');
    }

    public function getLocationInfo(): JsonResponse
    {
        
        $locationData = LocationHelper::getLocationInfo();
        Log::info('dghsfdhsd',$locationData);
        return response()->json($locationData);

       
    }
}
