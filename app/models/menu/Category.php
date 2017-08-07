<?php

namespace MyApp\models\menu;

use MyApp\models\Model;

class Category extends Model
{
    protected $id = 0;
    protected $ancestor = 0;
    protected $descendant = 0;
    protected $nearestAncestor = 0;
    protected $level = 0;
    protected $title;

    /**
     * @return int
     */
    public function getId()
    {
        return (int)$this->id;
    }

    /**
     * @return int
     */
    public function getAncestor()
    {
        return (int)$this->ancestor;
    }

    /**
     * @return int
     */
    public function getDescendant()
    {
        return (int)$this->descendant;
    }

    /**
     * @return int
     */
    public function getNearestAncestor()
    {
        return (int)$this->nearestAncestor;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return (int)$this->level;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $id
     * @return Category
     */
    public function setId($id)
    {
        if ($id > 0) {
            $this->id = (int)$id;
        }
        return $this;
    }

    /**
     * @param mixed $ancestor
     * @return Category
     */
    public function setAncestor($ancestor)
    {
        if ($ancestor >= 0) {
            $this->ancestor = (int)$ancestor;
        }
        return $this;
    }

    /**
     * @param mixed $descendant
     * @return Category
     */
    public function setDescendant($descendant)
    {
        if ($descendant > 0) {
            $this->descendant = (int)$descendant;
        }
        return $this;
    }

    /**
     * @param mixed $nearestAncestor
     * @return Category
     */
    public function setNearestAncestor($nearestAncestor)
    {
        if ($nearestAncestor >= 0) {
            $this->nearestAncestor = $nearestAncestor;
        }
        return $this;
    }

    /**
     * @param int $level
     * @return Category
     */
    public function setLevel($level)
    {
        if ($level > 0) {
            $this->level = (int)$level;
        }
        return $this;
    }

    /**
     * @param mixed $title
     * @return Category
     */
    public function setTitle($title)
    {
        if (is_string($title)) {
            $this->title = $title;
        }
        return $this;
    }


}