<?php
/**
 * Created by PhpStorm.
 * User: mrcake
 * Date: 12/13/17
 * Time: 6:13 PM
 */

namespace app;

use app\exceptions\NotFoundException;

class Router
{
    protected $routes = [];
    protected $currentRoute;

    public static function loadRoutes($path)
    {
        $router = new static();

        require $path;

        return $router;
    }

    public function loadRoutesByScript($path)
    {

    }

    public function getPath()
    {

    }

    public function getMethod()
    {

    }

    public function getParams()
    {

    }

    public function addRoute(string $path, $method, $processor, $parameters = [])
    {
        $newRouteRule = [];
        $newRouteRule['path'] = $path;
        $newRouteRule['methods'] = (array)$method;
        $newRouteRule['processor'] = $this->buildProcessor($processor);
        $newRouteRule['defaultParameters'] = $parameters;

        $this->routes[] = $newRouteRule;

        return $this;
    }

    /**
     * @param $processor
     * @return array
     */
    protected function buildProcessor($processor)
    {
        if (is_callable($processor)) {
            return $processor;
        } elseif (is_string($processor)) {
            $isInvokableController = strpos($processor, "::") === false;
            if (!$isInvokableController) {
                [$controller, $action] = explode("::", $processor);
                return [
                    'controller' => $controller,
                    'action' => $action,
                ];
            } else {
                return [
                    'controller' => $processor
                ];
            }
//            die(var_dump(__METHOD__, $isInvokableController));
        }

//        die(var_dump(__METHOD__));
    }

    public function get(string $path, $processor, $parameters = [])
    {
        return $this->addRoute($path, 'GET', $processor, $parameters);
    }

    public function post(string $path, $processor, $parameters = [])
    {
        return $this->addRoute($path, 'POST', $processor, $parameters);
    }

    public function adminOnly(bool $onlyAdmin)
    {
        if (!count($this->routes)) {
            throw new \InvalidArgumentException("No routes have specified yet");
        }

        $lastRoute = array_pop($this->routes);
        $lastRoute['checkers'] = $lastRoute['checkers'] ?? [];
        $lastRoute['checkers'][] = function () use ($onlyAdmin) {
            return $onlyAdmin === (Application::getInstance()->getRequest()->getUser() !== null);
        };

        $this->routes[] = $lastRoute;
        return $this;
    }

    /**
     * @param Request $request
     * @return array
     * @throws NotFoundException
     */
    public function getCurrentByRequest(Request $request)
    {
        foreach ($this->routes as $route) {
            if (!$this->checkRoutePath($request, $route['path'])) {
                continue;
            }

            if (!$this->checkRouteMethod($request, $route['methods'])) {
                continue;
            }

            if (isset($route['checkers']) && !$this->runRouteCheckers($route['checkers'])) {
                continue;
            }


            return array_values($route['processor']);
        }

//        echo '<pre>';die(var_dump(__METHOD__, $request, $this->routes));

        throw new NotFoundException("Cannot find route processor");
    }

    private function runRouteCheckers(array $checkers)
    {
        foreach ($checkers as $checker) {
            if (!call_user_func($checker)) {
                return false;
            }
        }

        return true;
    }

    private function checkRouteMethod(Request $request, $methods)
    {
        return in_array($request->getMethod(), $methods);
    }

    private function checkRoutePath(Request $request, $path)
    {
        return strpos($path, $request->getPath()) !== false;
    }
}
