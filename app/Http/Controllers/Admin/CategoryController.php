<?php

namespace App\Http\Controllers\Admin;

use Itstructure\GridView\DataProviders\EloquentDataProvider;
use App\Http\Controllers\Controller;
use App\Http\Requests\{StoreCategory, UpdateCategory, Delete};
use App\Models\Category;

/**
 * Class CategoryController
 *
 * @package App\Http\Controllers\Admin
 */
class CategoryController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $dataProvider = new EloquentDataProvider(Category::query());

        return view('admin.category.index', [
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * @param StoreCategory $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCategory $request)
    {
        Category::create($request->all());

        return redirect()->route('admin_category_list');
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(int $id)
    {
        $model = Category::findOrFail($id);

        return view('admin.category.edit', compact('model'));
    }

    /**
     * @param int $id
     * @param UpdateCategory $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(int $id, UpdateCategory $request)
    {
        Category::findOrFail($id)->fill($request->all())->save();

        return redirect()->route('admin_category_view', ['id' => $id]);
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

            Category::destroy($item);
        }

        return redirect()->route('admin_category_list');
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view(int $id)
    {
        $model = Category::findOrFail($id);

        return view('admin.category.view', compact('model'));
    }
}
