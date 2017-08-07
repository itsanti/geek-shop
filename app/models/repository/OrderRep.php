<?php

namespace MyApp\models\repository;


use MyApp\base\App;

/**
 * Class OrderRep
 * */
class OrderRep extends Repository
{
    protected $product_table;

    public function __construct()
    {
        parent::__construct();
        $this->product_table = App::call()->getConfig('components')['productRep']['table'];
    }

    public function fetchProductsByOrderId($order_id) {
        $table = $this->getJunctionTable();
        $sql = "SELECT p.* FROM `{$this->product_table}` p INNER JOIN `{$table}` op ON op.product_id = p.id WHERE op.order_id = :order_id";
        $params = [
            ":order_id" => $order_id
        ];
        $products = $this->conn->fetchAll($sql, $params);
        $numbers = $this->getNumberOfOneProduct($order_id);
        foreach ($products as &$product) {
            foreach ($numbers as $number) {
                if ($product['id'] == $number['product_id']) {
                    $product['number'] = $number['number'];
                }
            }
        }
        return $products;
    }

    public function fetchNotCompletedOrderByCId($customer_id)
    {
        $sql = "SELECT id FROM `{$this->table}` WHERE customer_id = :customer_id AND completed = FALSE ORDER BY id DESC";
        $params = [
            ":customer_id" => $customer_id
        ];
        return $this->conn->fetchOne($sql, $params);
    }

    public function proceedOrder($customer_id)
    {
      $sql = "UPDATE `{$this->table}` SET completed = TRUE WHERE customer_id = :customer_id AND completed = FALSE LIMIT 1";
      $params = [
          ":customer_id" => $customer_id
      ];
      $this->conn->execute($sql, $params);
    }

    public function saveOrder($product_id, $customer_id, $number = 1)
    {
        $order_id = App::call()->orderRep->fetchNotCompletedOrderByCId($customer_id)[0]['id'];
        if (null === $order_id) {
            $sql = "INSERT INTO `{$this->table}` (customer_id) VALUES (:customer_id)";
            $params = [
                ':customer_id' => $customer_id
            ];
            $this->conn->execute($sql, $params);
            $order_id = App::call()->orderRep->fetchNotCompletedOrderByCId($customer_id)[0]['id'];
        }
        $this->saveProduct($order_id, $product_id, $number);
    }

    public function deleteOrder($product_id, $customer_id)
    {
        $order_id = App::call()->orderRep->fetchNotCompletedOrderByCId($customer_id)[0]['id'];
        $this->deleteProduct($order_id, $product_id);
    }

    public function clearOrder($customer_id)
    {
        $order_id = App::call()->orderRep->fetchNotCompletedOrderByCId($customer_id)[0]['id'];
        $this->deleteAllProducts($order_id);
    }

    public function getNumberOfOneProduct($order_id)
    {
        $table = $this->getJunctionTable();
        $sql = "SELECT `product_id`, `number` FROM `{$table}` WHERE `order_id` = :order_id";
        $params = [
            ":order_id" => $order_id,
        ];
        $products = $this->conn->execute($sql, $params)->fetchAll();
        return $products;
    }

    public function getNumberOfProducts($user_id)
    {
        $order_id = App::call()->orderRep->fetchNotCompletedOrderByCId($user_id)[0]['id'];
        $table = $this->getJunctionTable();
        $sql = "SELECT COUNT(*) FROM `{$table}` WHERE `order_id` = :order_id";
        $params = [
            ":order_id" => $order_id
        ];
        $number = $this->conn->execute($sql, $params)->fetchColumn();
        return $number;
    }

    private function saveProduct($order_id, $product_id, $number)
    {
        $table = $this->getJunctionTable();
        $params = [
            ":order_id" => $order_id,
            ":product_id" => $product_id
        ];
        $sql_check = "SELECT * FROM `{$table}` WHERE order_id = :order_id AND product_id = :product_id";
        if (!$exists = $this->conn->execute($sql_check, $params)->rowCount()) {
            $params[":number"] = $number;
            $sql = "INSERT INTO `{$table}` (`order_id`, `product_id`, `number`) VALUES (:order_id, :product_id, :number)";
            $this->conn->execute($sql, $params);
        } else {
            $sql = "UPDATE `{$table}` SET `number` = `number` + 1 WHERE order_id = :order_id AND product_id = :product_id";
            $this->conn->execute($sql, $params);
        }
    }

    private function deleteProduct($order_id, $product_id)
    {
        $table = $this->getJunctionTable();
        $sql = "DELETE FROM `{$table}` WHERE order_id = :order_id AND product_id = :product_id";
        $params = [
            ":order_id" => $order_id,
            ":product_id" => $product_id
        ];
        $this->conn->execute($sql, $params);
    }

    private function deleteAllProducts($order_id)
    {
        $table = $this->getJunctionTable();
        $sql = "DELETE FROM `{$table}` WHERE order_id = :order_id";
        $params = [
            ":order_id" => $order_id
        ];
        $this->conn->execute($sql, $params);
    }

    protected function getJunctionTable()
    {
        return $this->table . "_" . $this->product_table;
    }
}
