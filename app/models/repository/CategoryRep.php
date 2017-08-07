<?php

namespace MyApp\models\repository;


class CategoryRep extends Repository
{
    protected $fields = [];
    protected $fieldsName = [];
    protected $nestedTable = "categoryTree";

    public function getTopCategories()
    {
        $sql = "SELECT * FROM `{$this->nestedTable}` WHERE level = 0";
        return $this->conn->execute($sql)->fetchAll();
    }

    public function getAll()
    {
        $sql = "SELECT * FROM `{$this->table}`";
        return $this->conn->fetchAll($sql);
    }

    public function createCategory($title, $ancestor_id)
    {

    }

    protected function getDataTree($id)
    {
        if ($id >= 0) {
            $data[] = (int)$id;
            $where[] = "{$this->nestedTable}.ancestor = ?";

            $fieldsTableData = $this
                ->getFields($this->table)
                ->getFieldsName($this->table);

            if (!empty($where) && !empty($data)) {
                $sql = "SELECT `{$this->table}`." . implode(", `{$this->table}`.", $fieldsTableData) . ",
                            categoryTree.ancestor,
                            categoryTree.descendant,
                            categoryTree.nearestAncestor,
                            categoryTree.level
                        FROM `{$this->table}` AS category
                        JOIN `{$this->nestedTable}` as categoryTree
                            ON category.id = categoryTree.descendant
                        WHERE " . implode(' AND ', $where) . "
                        ORDER BY categoryTree.ancestor ASC";
                $sql = str_replace(["\r", "\n"], "", $sql);

                if ($stmt = $this->conn->execute($sql, $data)) {
                    while ($item = $stmt->fetch()) {
                        $treeData[$item['id']] = $item;
                    }
                }
                if ($treeData) {
                    return $treeData;
                } else {
                    return false;
                }
            }
        }
    }

    protected function getFields($table)
    {
        $sql = "SHOW COLUMNS FROM `{$table}`";
        $this->fields[$table] = $this->conn->fetchAll($sql);
        return $this;
    }

    protected function getFieldsName($table)
    {
        if (!empty($table) && empty($this->fieldsName[$table])) {
            foreach ($this->fields[$table] as $item) {
                $this->fieldsName[$table][] = $item['Field'];
            }
        }

        return !empty($this->fieldsName[$table])
            ? $this->fieldsName[$table]
            : [];
    }

    public function getTree($id = null)
    {
        if (is_null($id) || $id === 0) {
            $categories = [];
            $top_categories = $this->getTopCategories();
            foreach ($top_categories as $key => $top) {
                $menu[$key] = $this->getDataTree($top['ancestor']);
                $categories += $menu[$key];
            }

            return $categories;
        }
        return $this->getDataTree($id);
    }

}