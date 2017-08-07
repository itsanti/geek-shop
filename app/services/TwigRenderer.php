<?php

namespace MyApp\services;


use MyApp\base\App;
use MyApp\services\interfaces\IRenderer;

class TwigRenderer implements IRenderer
{
    protected $templater;
    protected $templateDir;

    public function __construct()
    {
        $this->templateDir = App::call()->getConfig('root_dir') . "views/";
        $loader = new \Twig_Loader_Filesystem($this->templateDir);
        $this->templater = new \Twig_Environment($loader);
    }

    public function render($template, $params = [])
    {
        $template = "{$template}.twig";
        echo $this->templater->render($template, $params);
    }
}