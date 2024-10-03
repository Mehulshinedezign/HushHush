<?php

namespace App\Http\Controllers;

use App\Events\NewNotificationEvent;
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
        // $test = Storage::url('products\images\15_1724412712_3.jpg');
        // $imageData = @file_get_contents($test);
        // dd($request, $test, $imageData, 'hererer');
        event(new NewNotificationEvent("dfdsfsdfsdfsdfsdf"));

        return view('home');
    }

    public function fetch()
    {
        $notifications = auth()->user()->unreadNotifications;
        // dd($notifications);
        $html = view('modal.notification',compact('notifications'))->render();
        return response()->json(['status'=>true,'message' => "notifications fetched",'data'=>$html],200);
    }




    // public function markAsRead($id)
    // {
    //     $notification = auth()->user()->notifications()->find($id);

    //     if ($notification) {
    //         $notification->markAsRead();
    //     }

    //     return redirect()->back(); // Redirect back to the previous page
    // }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['status'=>true,'message' => "All notification is marked as read"],200);
    }
}
