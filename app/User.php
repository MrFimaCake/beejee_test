<?php
/**
 * Created by PhpStorm.
 * User: mrcake
 * Date: 12/13/17
 * Time: 11:04 PM
 */

namespace app;


class User
{
    public $username;

    public function __construct($username)
    {
        $this->username = $username;
    }
}