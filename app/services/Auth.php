<?php

namespace MyApp\services;

use MyApp\base\App;
use MyApp\models\user\User;

class Auth
{
    protected $sessionKey = 'sid';

    public function login($login, $password)
    {
        $user = App::call()->customerRep->getByLoginPass($login, $password);
        if (!$user) return false;
        $this->openSession($user);
        return $user;
    }

    public function logout()
    {
        if ($sid = $_SESSION[$this->sessionKey])
            App::call()->sessionsRep->clearSession($sid);
    }

    public function register($login, $password)
    {
        if(App::call()->customerRep->getByLogin($login)) {
            echo "Такой юзер уже существует";
            return false;
        }
        App::call()->customerRep->createCustomer($login, $password);
        return true;
    }

    public function openSession(User $user)
    {
        $sid = $this->generateStr();
        if ($user->getIsAuth()) {
            App::call()->sessionsRep->createNew($user->getId(), $sid, date("Y-m-d H:i:s"));
        }
        $_SESSION[$this->sessionKey] = $sid;
    }

    public function getSessionId(User $user)
    {
        $sid = $_SESSION[$this->sessionKey];
        if (!is_null($sid) && $user->getIsAuth()) {
            App::call()->sessionsRep->updateLastTime($sid);
        }
        return $sid;
    }

    private function generateStr($length = 10)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;

        while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0, $clen)];
        }
        return $code;
    }
}