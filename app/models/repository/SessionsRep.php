<?php

namespace MyApp\models\repository;

use MyApp\base\App;
use MyApp\models\products\Product;

/**
 * Class SessionsRep
 * */
class SessionsRep extends Repository
{

    public function clearSession($sid)
    {
        return $this->conn->execute(
            "DELETE FROM `{$this->table}` WHERE sid = :sid",
            [":sid" => $sid]
        );
    }

    public function clearSessions()
    {
        return $this->conn->execute(
            "DELETE FROM `{$this->table}` WHERE last_updated < ?;",
            []
        );
    }

    public function createNew($customerId, $sid, $timeLast)
    {
        return $this->conn->execute(
            "INSERT INTO `{$this->table}` (customer_id, sid, last_updated) VALUES (:customer_id, :sid, :last_updated);",
            [
                ":customer_id" => $customerId,
                ":sid" => $sid,
                ":last_updated" => $timeLast
            ]
        );
    }

    public function updateLastTime($sid, $time = null)
    {
        if (is_null($time)) {
            $time = date('Y-m-d H:i:s');
        }
        return $this->conn->execute(
            "UPDATE `{$this->table}` SET last_updated = \"{$time}\" WHERE sid = :sid;",
            [":sid" => $sid]
        );
    }

    public function getCIdBySId($sid)
    {
        $customer_id = $this->conn->fetchOne(
            "SELECT customer_id FROM `{$this->table}` WHERE sid = :sid;",
            [":sid" => $sid]
        );
        $customer_id =  $customer_id[0]['customer_id'];
        return $customer_id;
    }

    public function getNumberOfProductsGuest($sid)
    {
        return count($this->getProductsBySessionIdGuest($sid));
    }

    /**
     * @param $sid
     * @return array
     */
    public function getProductsBySessionIdGuest($sid)
    {
        return $_SESSION[$sid]['ordered_products'];
    }

    public function addProductToSessionGuest($sid, $product_id, $number = 1)
    {
        if ($this->getNumberOfProductsGuest($sid) >= App::call()->getConfig('guest_basket_max')) {
            return false;
        }
        if (isset($_SESSION[$sid]['ordered_products'])) {
            foreach ($_SESSION[$sid]['ordered_products'] as &$item) {
                foreach ($item as $key => $value) {
                    if ($key == "id" && $value == $product_id) {
                        $item['number'] += $number;
                        return true;
                    }
                }
            }
        }
        $product = App::call()->productRep->fetchProduct($product_id);
        $_SESSION[$sid]['ordered_products'][] = $product[0];
        $last = count($_SESSION[$sid]['ordered_products']) - 1;
        $_SESSION[$sid]['ordered_products'][$last]['number'] = 1;
        return true;
    }

    public function deleteProductFromSessionGuest($sid, $product_id)
    {
        if (isset($_SESSION[$sid]['ordered_products'])) {
            foreach ($_SESSION[$sid]['ordered_products'] as $product) {
                foreach ($product as $key => $value) {
                    if ($key == "id" && $value == $product_id) {
                        $index = array_search($product, $_SESSION[$sid]['ordered_products']);
                        unset($_SESSION[$sid]['ordered_products'][$index]);
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function clearOrder($sid)
    {
        if (isset($_SESSION[$sid]['ordered_products'])) {
            unset($_SESSION[$sid]['ordered_products']);
        }
        return true;
    }

}