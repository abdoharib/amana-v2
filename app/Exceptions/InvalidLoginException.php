<?php

namespace App\Exceptions;
use Exception;

class InvalidLoginException extends Exception
{
    /**
     * Report the exception.
     *
     * @return void
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
        $msg = "كلمة المرور او رقم الهاتف غير صحيح";
        return response()->json([
            $msg
        ],422);
    }
}
