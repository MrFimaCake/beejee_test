<?php
/**
 * Created by PhpStorm.
 * User: mrcake
 * Date: 12/14/17
 * Time: 12:29 AM
 */

namespace app;


class Response
{
    private $headers = [];
    private $outputString;

    public static function createWithOutputString($output)
    {
        $response = new static;
        $response->setOutput($output);
        return $response;
    }

    public function setOutput($output)
    {
        $this->outputString = $output;
    }

    public static function createWithRedirect($path)
    {
        $response = new static;
        $response->setRedirect($path);
        return $response;
    }

    public function setRedirect($path)
    {
        $this->headers[] = sprintf("Location: %s", $path);
    }

    public function send()
    {
        foreach ($this->headers as $header) {
            header($header);
        }

        echo $this->outputString;
        return true;
    }
}
