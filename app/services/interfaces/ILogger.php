<?php

namespace MyApp\services\interfaces;


interface ILogger
{
    public function log($prefix, $message);
}