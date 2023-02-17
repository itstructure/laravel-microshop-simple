<?php

namespace App\Services;

use Card;
use App\Models\Order as OrderModel;

/**
 * Class OrderService
 * @package App\Services
 */
class OrderService
{
    /**
     * @param int $modelId
     * @return Result
     */
    public function putToCard(int $modelId): Result
    {
        if (Card::putToCard($modelId, 1)) {
            return Result::getInstance()
                ->setSuccess(1)
                ->setTotalAmount(Card::getTotalAmount());

        } else {
            return Result::getInstance()
                ->setSuccess(0);
        }
    }

    /**
     * @param int $modelId
     * @param int $count
     * @return Result
     */
    public function setCountInCard(int $modelId, int $count): Result
    {
        if (Card::setCountInCard($modelId, $count)) {
            $modelItems = Card::getModelItems();
            return Result::getInstance()
                ->setSuccess(1)
                ->setTotalAmount(Card::calculateTotalAmount($modelItems))
                ->setItemPrice($modelItems[$modelId]->price);

        } else {
            return Result::getInstance()
                ->setSuccess(0);
        }
    }

    /**
     * @param int $modelId
     * @return Result
     */
    public function removeFromCard(int $modelId): Result
    {
        if (Card::removeFromCard($modelId)) {
            return Result::getInstance()
                ->setSuccess(1)
                ->setTotalAmount(Card::getTotalAmount());

        } else {
            return Result::getInstance()
                ->setSuccess(0);
        }
    }

    /**
     * @param array $orderData
     * @param array $cardCounts
     * @return Result
     */
    public function createOrder(array $orderData, array $cardCounts): Result
    {
        $order = new OrderModel();
        $order->fill($orderData);
        $order->save();

        $productCounts = array_map(function ($item) {
            return [
                'count' => $item
            ];
        }, $cardCounts);

        $order->products()->sync($productCounts);

        Card::clearCard();

        return Result::getInstance()
            ->setSuccess(1)
            ->setTotalAmount(0);
    }
}
