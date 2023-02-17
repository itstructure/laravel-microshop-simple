<?php

namespace App\Http\Controllers\Ajax;

use Order;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Http\Requests\{CardSend, CardCount, SendOrder};

/**
 * Class OrderAjaxController
 * @package App\Http\Controllers\Ajax
 */
class OrderAjaxController
{
    /**
     * @param CardSend $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function putToCard(CardSend $request)
    {
        try {
            $result = Order::putToCard($request->post('id'));

            return response()->json([
                'success' => $result->getSuccess(),
                'total_amount' => $result->getTotalAmount()
            ]);

        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    /**
     * @param CardCount $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setCountInCard(CardCount $request)
    {
        try {
            $result = Order::setCountInCard($request->post('id'), $request->post('count'));

            return response()->json([
                'success' => $result->getSuccess(),
                'total_amount' => $result->getTotalAmount(),
                'item_price' => $result->getItemPrice()
            ]);

        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    /**
     * @param CardSend $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeFromCard(CardSend $request)
    {
        try {
            $result = Order::removeFromCard($request->post('id'));

            return response()->json([
                'success' => $result->getSuccess(),
                'total_amount' => $result->getTotalAmount()
            ]);

        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    /**
     * @param SendOrder $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendOrder(SendOrder $request)
    {
        try {
            $result = Order::createOrder($request->all(), $request->post('card_counts'));

            return response()->json([
                'success' => $result->getSuccess(),
                'total_amount' => $result->getTotalAmount()
            ]);

        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}