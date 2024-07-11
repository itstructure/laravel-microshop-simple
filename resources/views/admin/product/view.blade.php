@extends('adminlte::page')
@section('title', 'View product')
@section('content')

    <section class="content container-fluid">
        <h2>Product: {{ $model->title }}</h2>

        <div class="row mb-3">
            <div class="col-12">
                <a class="btn btn-success d-inline" href="{{ route('admin_product_edit', ['id' => $model->id]) }}" title="Edit">Edit</a>
                <form action="{{ route('admin_product_delete') }}" method="post" class="d-inline">
                    <input type="submit" class="btn btn-danger" value="Delete product" title="Delete" onclick="return confirm('Sure?')">
                    <input type="hidden" value="{{ $model->id }}" name="items[]">
                    <input type="hidden" value="{!! csrf_token() !!}" name="_token">
                </form>
                <form action="{{ route('admin_product_delete') }}" method="post" class="d-inline">
                    <input type="submit" class="btn btn-danger" value="Delete total" title="Delete" onclick="return confirm('Sure?')">
                    <input type="hidden" value="1" name="remove_dependencies">
                    <input type="hidden" value="{{ $model->id }}" name="items[]">
                    <input type="hidden" value="{!! csrf_token() !!}" name="_token">
                </form>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <table class="table table-striped table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Attribute</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Thumbnail</td>
                            <td>
                                @if(!empty($thumbModel = $model->getThumbnailModel()))
                                    {!! \Itstructure\MFU\Facades\Previewer::getPreviewHtml($thumbModel, \Itstructure\MFU\Services\Previewer::LOCATION_FILE_INFO) !!}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Title</td>
                            <td>{{ $model->title }}</td>
                        </tr>
                        <tr>
                            <td>Alias</td>
                            <td>{{ $model->alias }}</td>
                        </tr>
                        <tr>
                            <td>Description</td>
                            <td>{{ $model->description }}</td>
                        </tr>
                        <tr>
                            <td>Price, $</td>
                            <td>{{ $model->price }}</td>
                        </tr>
                        <tr>
                            <td>Category</td>
                            <td><a href="{{ route('admin_category_view', ['id' => $model->category->id]) }}">{{ $model->category->title }}</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row mb-3">
            @include('uploader::partials.existing-mediafiles', ['mediaFiles' => $mediaFiles ?? []])
        </div>

        @if(!empty($relatedImageAlbums) && !$relatedImageAlbums->isEmpty())
            <hr />
            <h5>{{ trans('uploader::main.image_albums') }}</h5>
            <div class="row mb-3">
                @include('uploader::partials.albums-form-list', [
                    'albums' => $relatedImageAlbums
                ])
            </div>
        @endif
    </section>

@endsection
