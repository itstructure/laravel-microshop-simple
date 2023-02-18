@extends('adminlte::page')
@section('title', 'View category')
@section('content')

    <section class="content container-fluid">
        <h2>Category: {{ $model->title }}</h2>

        <div class="row mb-3">
            <div class="col-12">
                <form action="{{ route('admin_category_delete') }}" method="post">
                    <a class="btn btn-success" href="{{ route('admin_category_edit', ['id' => $model->id]) }}" title="Edit">Edit</a>
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
                    </tbody>
                </table>
            </div>
        </div>
    </section>

@endsection
