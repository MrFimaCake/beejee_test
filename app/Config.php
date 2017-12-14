<?php
/**
 * Created by PhpStorm.
 * User: mrcake
 * Date: 12/13/17
 * Time: 5:42 PM
 */

namespace app;


class Config
{
    /**
     * @var array $configParameters
     */
    protected $configParameters;

    public function __construct(array $config = null)
    {
        $this->configParameters = array_merge(
            $this->getDefaultParams(),
            $config ?? []
        );


    }

    /**
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public function __get(string $name)
    {
        if (!isset($this->configParameters[$name])) {
            throw new \Exception(sprintf("Invalid parameter `%s` called", $name));
        }

        return $this->configParameters[$name];
    }

    public function getDefaultParams()
    {
        $basePath = dirname(__DIR__);
        return [
            'routePath' => realpath($basePath . '/config/routes.php'),
            'layoutPath' => realpath($basePath . '/layouts'),
            'sqlitePath' => realpath($basePath . '/database') . '/db.db',
//            'uploadDir' => realpath($basePath . '/uploads'),
            'uploadDir' => './uploads',
        ];
    }

    public function getConfigValues()
    {
        return $this->configParameters;
    }

    public function getControllerNamespace()
    {
        return 'app\\controllers\\';
    }

    public function getRoutePath()
    {
        return $this->configParameters['routePath'];
    }

    public function getLayoutPath()
    {
        return $this->configParameters['layoutPath'];
    }

    public function getUploadDir()
    {
        return $this->configParameters['uploadDir'];
    }

    public function getSQLitePath()
    {
        return $this->configParameters['sqlitePath'];
    }

    public function getAllowedFileExtensions()
    {
        return $this->configParameters['allowedExtensions'] ?? [];
    }
}
