<?php

namespace App\Exceptions;

use Exception;

class BannedUserException extends Exception
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
        $msg = "تم حضر حسابك يرجى التواصل معنا لإعادة التفعيل";
        return response()->json([
            $msg
        ], 403);
    }
}
