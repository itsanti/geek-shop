<?php

namespace MyApp\models\repository;

/**
 * Class ProductRep
 * */
class ProductRep extends Repository
{
    public function fetchProduct($product_id)
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE id = :product_id";
        $params = [
            ":product_id" => $product_id
        ];
        return $this->conn->fetchOne($sql, $params);
    }

    public function deleteProduct($product_id)
    {
        $sql = "DELETE FROM `{$this->table}` WHERE id = :product_id";
        $params = [
            ":product_id" => $product_id
        ];
        $this->conn->execute($sql, $params);
    }

    public function createProduct($title, $alt, $desc, $price, $img_large, $img_small)
    {
        $sql = "INSERT INTO `{$this->table}` (title, alt, description, price, img_large, img_small) VALUES (:title, :alt, :desc, :price, :img_large, :img_small)";
        $params = [
            ":title" => $title,
            ":desc" => $desc,
            ":price" => $price,
            ":alt" => $alt,
            ":img_large" => $img_large,
            ":img_small" => $img_small,
        ];
        $this->conn->execute($sql, $params);
    }

    public function updateProduct($product_id, $title, $alt, $desc, $price, $img_large, $img_small)
    {
        $sql = "UPDATE `{$this->table}` SET title = :title, alt = :alt, description = :desc, price = :price, img_large = :img_large, img_small = :img_small WHERE id = :product_id";
        $params = [
            ":product_id" => $product_id,
            ":title" => $title,
            ":alt" => $alt,
            ":desc" => $desc,
            ":price" => $price,
            ":img_large" => $img_large,
            ":img_small" => $img_small,
        ];
        $this->conn->execute($sql, $params);
    }
}