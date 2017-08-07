<?php

namespace MyApp\controllers;


use MyApp\base\App;
use MyApp\services\interfaces\IRenderer;

abstract class Controller
{
    protected $action = null;
    protected $params = null;
    protected $defaultAction = "index";
    protected $templateRoot = "views";
    protected $layout = "main";

    protected static $user;


    public function __construct(IRenderer $renderer = null)
    {
        $this->renderer = $renderer;
    }

    public function run() {
        if (is_null($this->action) or $this->action == "") {
            $action = "action" . ucfirst($this->defaultAction);
        } else {
            $action = "action" . ucfirst($this->action);
        }
        if (!method_exists(get_class($this), $action)) {
            $action = "action404";
        }
        $this->$action();
    }

    public function action404() {
        $this->renderer->render('page/page404',
            [
                'layout' => false,
                'breadcrumbs' => false
            ]
        );
    }

    public function setAction($action) {
        $this->action = $action;
        return $this;
    }

    public function setParams($params) {
        $this->params = $params;
        return $this;
    }

    protected function getControllerName() {
        return strtolower(
            str_replace([App::call()->getConfig('controllers_namespace'), "Controller"], "", static::class)
        );
    }

    public function redirect($url)
    {
        header("Location: /{$url}");
    }

    protected function defineUser()
    {
        $user = App::call()->customer->getCurrent();
        if (!$user) {
            $user = App::call()->guest->getCurrent();
            if (!$user) {
                $user = App::call()->guest;
                App::call()->auth->openSession($user);
                $user->setId(App::call()->auth->getSessionId($user));
                $user = App::call()->guest->getCurrent();
            }
        }
        static::$user = $user;
    }
}