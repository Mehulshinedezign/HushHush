<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        dd($request,$test,$imageData);
        return view('home');
    }
}
