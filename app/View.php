<?php
/**
 * Created by PhpStorm.
 * User: mrcake
 * Date: 12/14/17
 * Time: 12:06 AM
 */

namespace app;

class View
{
    protected $layoutFile = 'main.php';
    protected $basePath;
    protected $context;

    public function __construct($basePath, $context)
    {
        $this->basePath = $basePath;
        $this->context = $context;
    }

    public function getContext()
    {
        return $this->context;
    }

    /**
     * Render form with given variables
     *
     * @param array $variables
     * @return string
     */
    public function render($filename, $variables = [])
    {
        ob_start();

        extract($variables);

        $title = "Simple title " . __METHOD__;

        require $this->basePath . $filename;

        $body = ob_get_clean();

        require $this->basePath . $this->layoutFile;

        $result = ob_get_clean();

        return $result;
    }
}