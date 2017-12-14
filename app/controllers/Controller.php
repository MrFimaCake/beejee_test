<?php
/**
 * Created by PhpStorm.
 * User: mrcake
 * Date: 12/13/17
 * Time: 11:37 PM
 */

namespace app\controllers;


use app\Config;
use app\DataMapper;
use app\exceptions\NotFoundException;
use app\Request;
use app\Response;
use app\View;

abstract class Controller
{
    protected $request;
    protected $config;
    protected $dataMapper;

    public function __construct(Request $request, Config $config, DataMapper $dataMapper)
    {
        $this->request = $request;
        $this->config = $config;
        $this->dataMapper = $dataMapper;
    }

    /**
     * @param $action
     * @return Response
     * @throws NotFoundException
     */
    public function callAction($action = null)
    {
        if (!is_callable([$this, $action])) {
            throw new NotFoundException("Unknown method");
        }


        return $result = $this->$action();
    }

    public function getDataMapper()
    {
        return $this->dataMapper;
    }

    public function getParams()
    {
        return $this->request->getRequestParams();
    }

    public function getParam(string $name, $defaultValue)
    {
        $params = $this->request->getRequestParams();
        return $params[$name] ?? $defaultValue;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function redirect($path)
    {
        return Response::createWithRedirect($path);
    }

    public function render($filename, array $params = [])
    {
        $layoutPath = $this->config->getLayoutPath() . '/';

        $view = new View($layoutPath, $this);

        return Response::createWithOutputString($view->render($filename, $params));
    }
}