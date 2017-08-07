<?php

namespace MyApp\controllers;

use MyApp\base\App;
use MyApp\models\Guest;

class FrontController extends Controller
{
    protected $controllerName;
    protected $actionName;
    protected $params;

    public function actionIndex() {
        try {
            $rm = App::call()->request;
            $this->controllerName = $rm->getControllerName();
            $this->actionName = $rm->getActionName();
            $this->params = $rm->getParams();
        } catch (\Exception $e) {
            $this->controllerName = get_class($this);
            $this->actionName = "404";
        }

        $this->checkUser();

        $class = App::call()->getConfig('controllers_namespace') . ucfirst($this->controllerName) . "Controller";
        if (!class_exists($class)) {
            $class = __CLASS__;
            $this->actionName = "404";
        }
        $controller = new $class(App::call()->renderer);
        $controller->setAction($this->actionName);
        $controller->setParams($this->params);
        $controller->run();
    }

    protected function checkUser()
    {
        session_start();
        $this->defineUser();
    }

}