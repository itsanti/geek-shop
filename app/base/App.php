<?php

namespace MyApp\base;

include $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "services" . DIRECTORY_SEPARATOR . "traits" . DIRECTORY_SEPARATOR . "TSingleton.php";

use Composer\DependencyResolver\Request;
use MyApp\controllers\Controller;
use MyApp\models\menu\Category;
use MyApp\models\repository\CategoryRep;
use MyApp\models\user\Customer;
use MyApp\models\user\Guest;
use MyApp\models\repository\CustomerRep;
use MyApp\models\Order;
use MyApp\models\repository\OrderRep;
use MyApp\models\repository\ProductRep;
use MyApp\models\repository\SessionsRep;
use MyApp\services\Auth;
use MyApp\services\Db;
use MyApp\services\FileLogger;
use MyApp\services\interfaces\IRenderer;
use MyApp\services\traits\TSingleton;

/**
 * Class App
 * @package MyApp\base
 * @property Controller mainController
 * @property Customer customer
 * @property Guest guest
 * @property CustomerRep customerRep
 * @property IRenderer renderer
 * @property SessionsRep sessionRep
 * @property Auth auth
 * @property Order order
 * @property OrderRep orderRep
 * @property Request request
 * @property ProductRep productRep
 * @property Category category
 * @property CategoryRep categoryRep
 * @property Db db
 * @property FileLogger fileLogger
 */
class App
{
    protected $config;
    /** @var Container */
    protected $storage;

    use TSingleton;

    public static function call()
    {
        return static::getInstance();
    }

    public function run()
    {
        $this->config = include $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "main.php";
        $this->autoload();
        $this->storage = new Container();
        $this->mainController->run();
    }

    public function getConfig($name)
    {
        return $this->config[$name];
    }

    protected function autoload()
    {
        require_once $this->config['root_dir'] . ".." . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";
    }

    public function createComponent($name)
    {
        if (isset($this->config['components'][$name])) {
            $params = $this->config['components'][$name];
            $class = $params['class'];
            unset($params['class']);
            $reflection = new \ReflectionClass($class);
            return $reflection->newInstanceArgs($params);
        }
    }

    public function __get($name)
    {
        return $this->storage->get($name);
    }
}