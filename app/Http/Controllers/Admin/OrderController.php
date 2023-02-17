<?php

namespace App\Http\Controllers\Admin;

use Itstructure\GridView\DataProviders\EloquentDataProvider;
use App\Http\Controllers\Controller;
use App\Http\Requests\Delete;
use App\Models\Order;

/**
 * Class OrderController
 *
 * @package App\Http\Controllers\Admin
 */
class OrderController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $dataProvider = new EloquentDataProvider(Order::with('products'));

        return view('admin.order.index', [
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * @param Delete $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Delete $request)
    {
        foreach ($request->items as $item) {

            if (!is_numeric($item)) {
                continue;
            }

            Order::destroy($item);
        }

        return redirect()->route('admin_order_list');
    }
}
