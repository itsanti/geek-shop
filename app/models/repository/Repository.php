<?php

namespace MyApp\models\repository;


use MyApp\base\App;
use MyApp\services\Db;

/**
* Class Repository
* @property Db conn
* */
abstract class Repository
{
    protected $conn;
    protected $nestedClass;
    protected $table;
    protected $repEntity;
    protected $entity;

    public function __construct()
    {
        $this->conn = App::call()->db;
        $this->repEntity = $this->getRepEntity();
        $this->entity = $this->getEntity();
        $this->nestedClass = App::call()->getConfig('components')[$this->entity]['class'];
        $this->table = App::call()->getConfig('components')[$this->repEntity]['table'];
    }

    public function getById($id)
    {
        return $this->conn->fetchObject(
            "SELECT c.* FROM `{$this->table}` c WHERE c.id = :id",
            [":id" => $id],
            $this->nestedClass
        );
    }

    public function getRandomLimit($limit) {
        $sql = "SELECT * FROM `{$this->table}` ORDER BY RAND() LIMIT :limit";
        $params = [
            ":limit" => $limit,
        ];
        return $this->conn->fetchRandomLimit($sql, $params);
    }

    public function getAll() {
        return $this->conn->fetchAll("SELECT * FROM `{$this->table}`");
    }

    private function getRepEntity()
    {
        return lcfirst(array_pop(explode('\\', $this->getClassName())));
    }

    private function getEntity()
    {
        return str_replace("Rep", "", $this->getRepEntity());
    }

    private function getClassName()
    {
        return get_called_class();
    }
}