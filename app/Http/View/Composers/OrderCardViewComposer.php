<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Card;

/**
 * Class OrderCardViewComposer
 * @package App\Http\View\Composers
 */
class OrderCardViewComposer extends TopCardViewComposer
{
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        parent::compose($view);

        $view->with('cardProducts', $this->cardProducts);
        $view->with('cardCounts', Card::retrieveSessionData());
    }
}