<?php
/**
 * Created by PhpStorm.
 * User: mrcake
 * Date: 12/13/17
 * Time: 5:59 PM
 */

namespace app;


use app\exceptions\ValidationException;
use app\lib\Eventviva\ImageResizeException;
use Eventviva\ImageResize;

class Request
{
    protected $path;
    protected $uploadDir;
    protected $fileExtensions;
    protected $method;
    protected $user;

    const IMAGE_MAX_WIDTH = 320;
    const IMAGE_MAX_HEIGHT = 240;

    public function __construct(Config $config)
    {
        $this->path = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD'];

        $this->uploadDir = $config->getUploadDir() . DIRECTORY_SEPARATOR;
        $this->fileExtensions = $config->getAllowedFileExtensions();

        $this->initUser();
    }

    public function getPath()
    {
        $questPos = strpos($this->path, '?');
        return $questPos === false
            ? substr($this->path, 0)
            : substr($this->path, 0, $questPos);
    }

    public function getUri()
    {
        return $this->path;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function initUser()
    {
        if (isset($_SESSION['username'])) {
            $this->user = new User($_SESSION['username']);
        }
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getRequestParams()
    {
        return $_REQUEST;
    }

    /**
     * @param string $filekey
     * @return bool|string
     * @throws ValidationException
     */
    public function loadFile(string $filekey)
    {
        $fileRequest = $_FILES[$filekey] ?? false;

        if (!$filekey || !$fileRequest || !$fileRequest['size']) {
            return false;
        }

        if (count($this->fileExtensions) && !in_array($fileRequest['type'], $this->fileExtensions)) {
            throw new ValidationException("Now allowed file extensions");
        }


        [$width, $height] = getimagesize($fileRequest['tmp_name']);


        $overWidth = $width / self::IMAGE_MAX_WIDTH;
        $overHeight = $height / self::IMAGE_MAX_HEIGHT;

        $filepath = $this->uploadDir . $fileRequest['name'];

        if ($overWidth > 1 || $overHeight > 1) {
            $requiredScale = max($overWidth, $overHeight);

            $newWidth = $width / $requiredScale;
            $newHeight = $height / $requiredScale;

            try {

                $resizer = new \app\lib\Eventviva\ImageResize($fileRequest['tmp_name']);

                $resizer->resizeToHeight($newHeight);
                $resizer->resizeToWidth($newWidth);

                if ($resizer->save($filepath)) {
                    return substr($filepath, 1);
                }

            } catch (ImageResizeException $e) {

            }
        } else {
            if (copy($fileRequest['tmp_name'], $filepath)) {
                return substr($filepath, 1);
            }
        }



        return false;
    }
}
