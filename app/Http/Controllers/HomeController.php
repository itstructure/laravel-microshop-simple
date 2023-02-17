<?php

namespace App\Http\Controllers;

use App\Models\{Product, Category};

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * @var int
     */
    protected $rowsInPage;

    /**
     * HomeController constructor.
     */
    public function __construct()
    {
        $this->rowsInPage = config('app.rowsInPage');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $products = Product::paginate($this->rowsInPage);

        return view('home', compact('products'));
    }

    /**
     * @param string $alias
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function products(string $alias)
    {
        $category = Category::getByAlias($alias);

        if (empty($category)) {
            abort(404);
        }

        $products = $category->products()->paginate($this->rowsInPage);

        return view('home', compact('products', 'category'));
    }
}
