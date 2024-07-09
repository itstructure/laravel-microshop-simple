<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Itstructure\GridView\DataProviders\EloquentDataProvider;
use Itstructure\MFU\Models\Owners\{OwnerMediafile, OwnerAlbum};
use Itstructure\MFU\Models\Albums\{ImageAlbum};
use Itstructure\MFU\Processors\SaveProcessor;
use App\Http\Controllers\Controller;
use App\Http\Requests\{StoreProduct, UpdateProduct, Delete};
use App\Models\{Product, Category};

/**
 * Class ProductController
 *
 * @package App\Http\Controllers\Admin
 */
class ProductController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $dataProvider = new EloquentDataProvider(Product::query());

        return view('admin.product.index', [
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::pluck('title', 'id')->toArray();
        $allImageAlbums = $this->getAllImageAlbums();

        return view('admin.product.create', compact('categories', 'allImageAlbums'));
    }

    /**
     * @param StoreProduct $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreProduct $request)
    {
        Product::create($request->all());

        return redirect()->route('admin_product_list');
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(int $id)
    {
        $model = Product::findOrFail($id);

        $categories = Category::pluck('title', 'id')->toArray();
        $mediaFiles = $this->getMediaFiles($model);
        $allImageAlbums = $this->getAllImageAlbums($model);

        return view('admin.product.edit', compact('model', 'categories', 'mediaFiles', 'allImageAlbums'));
    }

    /**
     * @param int $id
     * @param UpdateProduct $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(int $id, UpdateProduct $request)
    {
        Product::findOrFail($id)->update($request->all());

        return redirect()->route('admin_product_view', ['id' => $id]);
    }

    /**
     * @param Delete $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Delete $request)
    {
        foreach ($request->items as $id) {

            if (!is_numeric($id)) {
                continue;
            }

            Product::find($id)
                ->setRemoveDependencies(!empty($request->get('remove_dependencies')))
                ->delete();
        }

        return redirect()->route('admin_product_list');
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view(int $id)
    {
        $model = Product::findOrFail($id);
        $mediaFiles = $this->getMediaFiles($model);
        $relatedImageAlbums = $this->getRelatedImageAlbums($model);

        return view('admin.product.view', compact('model', 'mediaFiles', 'relatedImageAlbums'));
    }

    /**
     * @param Product $model
     * @return EloquentCollection
     */
    protected function getMediaFiles(Product $model): EloquentCollection
    {
        return OwnerMediafile::getMediaFiles($model->getItsName(), $model->id, SaveProcessor::FILE_TYPE_IMAGE);
    }

    /**
     * @param Product|null $model
     * @return SupportCollection
     */
    protected function getAllImageAlbums(Product $model = null): SupportCollection
    {
        return !is_null($model) ? ImageAlbum::getAllForOwner($model) : ImageAlbum::getAll();
    }

    /**
     * @param Product $model
     * @return EloquentCollection
     */
    protected function getRelatedImageAlbums(Product $model): EloquentCollection
    {
        return OwnerAlbum::getImageAlbums($model->getItsName(), $model->getPrimaryKey());
    }
}
