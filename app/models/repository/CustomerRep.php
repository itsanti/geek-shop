<?php

namespace MyApp\models\repository;

use MyApp\base\App;
use MyApp\models\Customer;

/**
 * Class CustomerRep
 * */
class CustomerRep extends Repository
{

    /**
     * @param $login
     * @param $password
     * @param Customer customer
     * @return Customer | bool
     */
    public function getByLoginPass($login, $password)
    {
        $customer = $this->getByLogin($login);
        if ($customer && $this->verifyPassword($password, $customer->getPassword())) {
            return $customer;
        }
        return false;
    }

    public function getByLogin($login)
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE login = :login";
        $customer = $this->conn->fetchObject(
            $sql,
            [":login" => $login],
            $this->nestedClass
        );
        if ($customer) {
            return $customer;
        } else {
            return false;
        }
    }

    private function hashPassword($password)
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        return $hash;
    }

    public function verifyPassword($password, $hash)
    {
        $result = password_verify($password, $hash);
        return $result;
    }

    public function deleteCustomer($id)
    {
        $sql = "DELETE FROM `{$this->table}` WHERE id = :id";
        $params = [
            ":id" => $id
        ];
        $this->conn->execute($sql, $params);
    }

    public function createCustomer($login, $password)
    {
        $password = $this->hashPassword($password);
        $sql = "INSERT INTO `{$this->table}` (`login`, `password`) VALUES (:login, :password)";
        $params = [
            ":login" => $login,
            ":password" => $password,
        ];
        $this->conn->execute($sql, $params);
    }

    public function updateCustomer($id, $login, $is_admin, $password = null)
    {
        if (is_null($password)) {
            $password = App::call()->customerRep->getById($id)->getPassword();
        }
        $sql = "UPDATE `{$this->table}` SET `login` = :login, is_admin = :is_admin, `password` = :password WHERE id = :id";
        $params = [
            ":id" => $id,
            ":login" => $login,
            ":is_admin" => $is_admin,
            ":password" => $password,
        ];
        $this->conn->execute($sql, $params);
    }
}