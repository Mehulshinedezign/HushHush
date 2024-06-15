<?php
 
namespace App\Exceptions;
 
use Exception;
 
class AjaxValidationException extends Exception
{
    private $validator;
    public function __construct( $validator ){
        $this->validator = $validator;
    }
    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        //
    }
 
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        $response = [
            'success'       =>  false,
            'msg'           =>  $this->validator->errors()->all()
        ];
        if( request('errortype') )
            $response['errortype'] = request('errortype');
        return response()->json( $response );
    }
}