<?php

namespace MyApp\models;


class Product extends Model
{
    protected $id;
    protected $title;
    protected $alt;
    protected $description;
    protected $price;
    protected $category_id;
    protected $img_large;
    protected $img_small;

    /**
     * Product constructor.
     * @param string|null $title
     * @param string|null $alt
     * @param string|null $description
     * @param float|null $price
     * @param int|null $category_id
     * @param string|null $img_large
     * @param string|null $img_small
     */
    public function __construct(string $title = null, string $alt = null, string $description = null, float $price = null, int $category_id = null, string $img_large = null, string $img_small = null)
    {
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->category_id = $category_id;
        $this->img_large = $img_large;
        $this->img_small = $img_small;
        $this->alt = $alt;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price)
    {
        $this->price = $price;
    }

    /**
     * @return int|null
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * @param int|null $category_id
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getTitle();
    }

    public function getImgLarge()
    {
        return $this->img_large;
    }

    public function getImgSmall()
    {
        return $this->img_small;
    }


}