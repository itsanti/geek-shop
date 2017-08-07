<?php

namespace MyApp\models\user;

use MyApp\models\Model;

abstract class User extends Model
{
    protected $is_auth;
    protected $login;
    protected $is_admin;
    protected $id;

    public function getIsAuth()
    {
        return $this->is_auth;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function getIsAdmin()
    {
        return $this->is_admin;
    }

    public function getId()
    {
        return $this->id;
    }
}