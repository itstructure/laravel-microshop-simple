@section('title', 'Edit product')
@extends('adminlte::page')
@section('content')

    <section class="content container-fluid">
        <div class="row">
            <div class="col-12">

                <h2>Edit product</h2>

                <form action="{{ route('admin_product_update', ['id' => $model->id]) }}" method="post">

                    @include('admin.product._fields')

                    <button class="btn btn-primary" type="submit">Submit</button>

                    <input type="hidden" value="{!! csrf_token() !!}" name="_token">

                </form>

            </div>
        </div>
    </section>

@stop