<?php

namespace MyApp\models;

use MyApp\base\App;

abstract class Model
{
    public static function getConn() {
        return App::call()->db;
    }
}