@extends('adminlte::page')
@section('title', 'View product')
@section('content')

    <section class="content container-fluid">
        <h2>Product: {{ $model->title }}</h2>

        <div class="row mb-3">
            <div class="col-12">
                <form action="{{ route('admin_product_delete') }}" method="post">
                    <a class="btn btn-success" href="{{ route('admin_product_edit', ['id' => $model->id]) }}" title="Edit">Edit</a>
                    <input type="submit" class="btn btn-danger" value="Delete" title="Delete" onclick="return confirm('Sure?')">
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
    </section>

@endsection
