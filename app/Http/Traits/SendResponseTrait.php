<?php
namespace App\Http\Traits;
 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
 
trait SendResponseTrait
{
 
  public function apiResponse($apiResponse, $statusCode = '400', $message = 'No records Found', $data = [], $isVerified)
  {
      $responseArray = [];
      $otherDetail = [];
  
      if ($data instanceof MessageBag)
          $data = $data->messages();
  
      $responseArray['status'] = $apiResponse === 'success';
      $responseArray['message'] = $message;
      $responseArray['verify'] = $isVerified;
      $responseArray['data'] = array_merge($data, $otherDetail);
  
      return response()->json($responseArray, $statusCode);
  }
  
 
}