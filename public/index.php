<?php
header("Content-Type: text/html; charset=utf-8");

include ".." . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "base" . DIRECTORY_SEPARATOR . "App.php";

\MyApp\base\App::call()->run();

