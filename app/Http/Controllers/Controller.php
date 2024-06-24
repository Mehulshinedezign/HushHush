<?php

namespace App\Http\Controllers;

use App\Http\Traits\SendResponseTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Notification;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests,SendResponseTrait;

    protected function sendNotification($data)
    {
        return Notification::insert($data);
    }
}
