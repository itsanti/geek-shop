<?php

namespace MyApp\services;


use MyApp\base\App;

class RequestManager
{
    protected $requestString;

    protected $controllerName;
    protected $actionName;
    protected $params;
    protected $rules = [
        "^product/([0-9]+)" => "product/index/$1",
        "^login$" => "auth/index",
        "^logout$" => "auth/logout",
        "^register$" => "auth/register",
        "^order$" => "order/index",
        "^catalog$" => "catalog/index",
        "^home$" => "home/index",
        "^admin$" => "admin/index",
        "^$" => "home/index"
    ];
    protected $pattern = "#(?P<controller>\w+)[/]?(?P<action>\w+)?[/]?(?P<params>.*)#u";

    public function __construct()
    {
        if (!$this->parseRequest()) {
            throw new \Exception();
        }
    }

    private function getURI(){
        if(!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }

        if(!empty($_SERVER['PATH_INFO'])) {
            return trim($_SERVER['PATH_INFO'], '/');
        }

        if(!empty($_SERVER['QUERY_STRING'])) {
            return trim($_SERVER['QUERY_STRING'], '/');
        }
    }

    private function parseRequest() {
        $this->requestString = $this->getURI();
        foreach ($this->rules as $pattern => $route)
        {
            if (preg_match("#$pattern#", $this->requestString)) {
                $internalRoute = preg_replace("#$pattern#", $route, $this->requestString);
                $seqments = explode('/', $internalRoute);
                $this->controllerName = array_shift($seqments);
                $this->actionName = array_shift($seqments);
                $this->params = $seqments;
                return true;
            }
        }
        if (preg_match_all($this->pattern, $this->requestString, $matches)) {
            $this->controllerName = $matches['controller'][0];
            $this->actionName = $matches['action'][0];
            $this->params = array_merge(
                explode("/", $matches['params'][0]),
                $_GET,
                $_POST
            );
            return true;
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getControllerName()
    {
        return $this->controllerName;
    }

    /**
     * @return mixed
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    public function getRefererAction($url)
    {
        $referer = str_replace(App::call()->getConfig('domain'), "", $url);
        $referer = str_replace(":", "", $referer);
        $referer = str_replace(App::call()->getConfig('port'), "", $referer);
        $referer = substr_replace($referer, "", 0, 1);
        return $referer;
    }
}