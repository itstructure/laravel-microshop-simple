<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Card;

/**
 * Class TopCardViewComposer
 * @package App\Http\View\Composers
 */
class TopCardViewComposer
{
    /**
     * @var array
     */
    protected $cardProducts = [];

    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $this->cardProducts = Card::getModelItems();

        $view->with('totalAmount', Card::calculateTotalAmount($this->cardProducts));
    }
}