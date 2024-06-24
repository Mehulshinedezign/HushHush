<?php
namespace App\Http\Traits;
 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
 
trait SendResponseTrait
{
 
  public function apiResponse($apiResponse, $statusCode = '400', $message = 'No records Found', $data = [])
  {
 
      $responseArray = [];
      $otherDetail = [];
 
      if ($data instanceof MessageBag)
        $data = $data->messages();
 
     
 
      if ($apiResponse == 'success') {
 
        $responseArray['status'] = true;
        $responseArray['message'] = $message;
        $responseArray['data'] = array_merge($data, $otherDetail);
      } else {
 
        $responseArray['status'] = false;
        $responseArray['message'] = $message;
        $responseArray['data'] = array_merge($data, $otherDetail);
      }
 
      return response()->json($responseArray  , $statusCode );
  }
 
}