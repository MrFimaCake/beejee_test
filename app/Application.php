<?php
/**
 * Created by PhpStorm.
 * User: mrcake
 * Date: 12/13/17
 * Time: 5:41 PM
 */

namespace app;


use app\controllers\Controller;
use app\controllers\ErrorController;
use app\exceptions\NotFoundException;

class Application
{
    public static $_instance;

    /** @var Config $config */
    protected $config;

    /** @var Request $request */
    protected $request;

    /** @var Router $router */
    protected $router;

    protected $user;

    private function __construct(Config $config, Request $request)
    {
        $this->config = $config;
        $this->request = $request;

        $this->router = Router::loadRoutes($config->routePath);
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    public static function init(array $configValues) : self
    {
        $config = new Config($configValues);

        $request = new Request($config);

        $selfInstance = new self($config, $request);

        return self::$_instance = $selfInstance;
    }

    public static function getInstance() : self
    {
        return self::$_instance;
    }

    public function getRequest() : Request
    {
        return $this->request;
    }

    public function run()
    {
        try {
            $dataMapper = new DataMapper(new \PDO("sqlite:" . $this->config->getSQLitePath()));

            [$controllerAlias, $action] = $this->router->getCurrentByRequest($this->request);

            $controllerClassname = $this->config->getControllerNamespace() . $controllerAlias;


            /** @var Controller $controller */
            $controller = new $controllerClassname(
                $this->request,
                $this->config,
                $dataMapper
            );

            $response = $controller->callAction($action);

            return $response->send();
        } catch (NotFoundException $e) {

            $controller = new ErrorController($this->request, $this->config, $dataMapper);

            return $controller(404, $e->getMessage())->send();
        } catch (\PDOException $e) {

            $controller = new ErrorController($this->request, $this->config, $dataMapper);

            return $controller(404, "Database error")->send();
        } catch (\Exception $e) {
            return Response::createWithRedirect('/')->send();
        }

    }
}
