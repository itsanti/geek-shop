<?php

namespace MyApp\models\user;


use MyApp\base\App;

class Guest extends User
{
    public function __construct()
    {
        $this->is_auth = false;
        $this->login = "Guest";
        $this->is_admin = false;
        $this->id = App::call()->auth->getSessionId($this);
    }

    public function getCurrent()
    {
        $sid = App::call()->auth->getSessionId($this);
        if (!is_null($sid)) {
            return $this;
        }
        return null;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
}