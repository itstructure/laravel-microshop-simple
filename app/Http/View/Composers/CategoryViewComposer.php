<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\Category;

/**
 * Class CategoryViewComposer
 * @package App\Http\View\Composers
 */
class CategoryViewComposer
{
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with('categories', Category::all());
    }
}
