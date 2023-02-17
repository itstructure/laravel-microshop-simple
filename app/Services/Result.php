<?php

namespace App\Services;

/**
 * Class Result
 * @package App\Services
 */
class Result
{
    /**
     * @var int
     */
    private $success = 0;

    /**
     * @var float
     */
    private $total_amount = 0;

    /**
     * @var float
     */
    private $item_price = 0;

    /**
     * @return static
     */
    public static function getInstance()
    {
        return new static();
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setSuccess(int $value)
    {
        $this->success = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getSuccess(): int
    {
        return $this->success;
    }

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->success == 1;
    }

    /**
     * @param float $value
     * @return $this
     */
    public function setTotalAmount(float $value)
    {
        $this->total_amount = $value;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalAmount(): float
    {
        return $this->total_amount;
    }

    /**
     * @param float $value
     * @return $this
     */
    public function setItemPrice(float $value)
    {
        $this->item_price = $value;

        return $this;
    }

    /**
     * @return float
     */
    public function getItemPrice(): float
    {
        return $this->item_price;
    }
}