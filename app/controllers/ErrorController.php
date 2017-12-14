<?php
/**
 * Created by PhpStorm.
 * User: mrcake
 * Date: 12/14/17
 * Time: 6:08 PM
 */

namespace app\controllers;


class ErrorController extends Controller
{
    public function __invoke($errorCode = 404, $errorMessage = "")
    {
        return $this->render('error.php',[
            'code' => $errorCode,
            'message' => $errorMessage
        ]);
    }
}
