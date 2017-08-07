<?php

namespace MyApp\models\user;

use MyApp\base\App;

class Customer extends User

{
    protected $password;

    /**
     * Customer constructor.
     * @param string $login
     * @param string $password
     * @param bool $is_admin
     */
    public function __construct($login = null, $password = null, $is_admin = false)
    {
        $this->login = $login;
        $this->password = $password;
        $this->is_admin = $is_admin;
        $this->is_auth = true;
    }

    public function getCurrent()
    {
        if ($customerId = $this->getCustomerId()) {
            $customer = App::call()->customerRep->getById($customerId);
            return $customer;
        }
        return null;
    }

    protected function getCustomerId()
    {
        $sid = App::call()->auth->getSessionId(App::call()->customer);
        if (!is_null($sid)) {
            return App::call()->sessionsRep->getCIdBySId($sid);
        }
        return null;
    }

    /**
     * @param string $login
     */
    public function setLogin(string $login)
    {
        $this->login = $login;
    }

    /**
     * @param bool $is_admin
     */
    public function setIsAdmin(bool $is_admin)
    {
        $this->is_admin = $is_admin;
    }

    public function getPassword() {
        return $this->password;
    }
}