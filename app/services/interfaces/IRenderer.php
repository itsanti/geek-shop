<?php

namespace MyApp\services\interfaces;


interface IRenderer
{
    public function render($template, $params);
}