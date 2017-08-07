<?php

namespace MyApp\models;

class Order extends Model
{
    protected $id;
    protected $customer_id;
    protected $completed;
    protected $datetime;

    /**
     * Order constructor.
     * @param int $customer_id
     */
    public function __construct($customer_id = null)
    {
        $this->customer_id = $customer_id;
    }

    /**
     * @return int
     */
    public function getCustomerId()
    {
        return $this->customer_id;
    }

    /**
     * @param int $customer_id
     */
    public function setCustomerId($customer_id)
    {
        $this->customer_id = $customer_id;
    }

    /**
     * @return string
     */
    public function getDatetime()
    {
        return (string)$this->datetime;
    }

    /**
     * @param \DateTime $datetime
     */
    public function setDatetime(\DateTime $datetime)
    {
        $this->datetime = $datetime;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * @param bool $completed
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;
    }
}