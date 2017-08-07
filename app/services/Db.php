<?php

namespace MyApp\services;


use MyApp\base\App;
use MyApp\services\interfaces\ILogger;

/**
 * Class Db
 * @package MyApp\services
 * @property ILogger logger
 */
class Db
{
    protected $conn;
    protected $config;
    protected $logger;

    /**
     * Db constructor.
     * @param $driver
     * @param $host
     * @param $dbname
     * @param $user
     * @param $password
     */
    public function __construct($driver, $host, $dbname, $user, $password)
    {
        $this->config['driver'] = $driver;
        $this->config['host'] = $host;
        $this->config['user'] = $user;
        $this->config['password'] = $password;
        $this->config['dbname'] = $dbname;
    }

    protected function getConnection() {
        $this->logger = App::call()->fileLogger;
        try {
            if (is_null($this->conn)) {
                $this->conn = new \PDO(
                    $this->prepareDsnString(),
                    $this->config['user'],
                    $this->config['password']
                );
                $this->getConnection()->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
                $this->getConnection()->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            }
            return $this->conn;
        } catch (\PDOException $e) {
            $this->logger->log("PDO", $e->getMessage());
        }
    }

    private function prepareDsnString() {
        $result = sprintf(
            "%s:host=%s;dbname=%s;charset=UTF8",
            $this->config['driver'], $this->config['host'], $this->config['dbname']
        );
        return $result;
    }

    private function query($sql, $params = []) {
        try {
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (\PDOException $e) {
            $this->logger->log("PDO", $e->getMessage());
        }
    }

    private function queryRandomLimit($sql, $params) {
        try {
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindValue(':limit', $params[':limit'], \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt;
        } catch (\PDOException $e) {
            $this->logger->log("PDO", $e->getMessage());
        }
    }

    public function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll();
    }

    public function fetchOne($sql, $params = []) {
        return $this->fetchAll($sql, $params);
    }

    public function execute($sql, $params = []) {
        return $this->query($sql, $params);
    }

    public function fetchCount($sql, $params = []) {
        return $this->query($sql, $params)->fetchColumn();
    }

    public function fetchObject($sql, $params = [], $class) {
        $stmt = $this->query($sql, $params);
        $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $class);
        return $stmt->fetch();
    }

    public function fetchRandomLimit($sql, $params) {
        $stmt = $this->queryRandomLimit($sql, $params);
        return $stmt->fetchAll();
    }
}