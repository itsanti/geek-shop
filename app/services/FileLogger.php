<?php

namespace MyApp\services;


use MyApp\base\App;
use MyApp\services\interfaces\ILogger;

class FileLogger implements ILogger
{
    public function log($prefix, $message) {
        echo "Что-то пошло не так...";
        if (App::call()->getConfig('is_logging')) {
            file_put_contents(App::call()->getConfig('logs_path') . $prefix ."_Errors.txt", date('Y-m-d H:i:s') . ": " . $message . "\n", FILE_APPEND);
        } else {
            echo $message;
        }
    }
}