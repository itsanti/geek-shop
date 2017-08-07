<?php

namespace MyApp\services\traits;


trait TSingleton
{
    protected static $instance;

    public static function getInstance() {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    private function __construct(){}
    private function __wakeup(){}
    private function __clone(){}
}