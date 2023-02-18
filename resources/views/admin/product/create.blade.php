@section('title', 'Create product')
@extends('adminlte::page')
@section('content')

    <section class="content container-fluid">
        <div class="row">
            <div class="col-12">

                <h2>Create product</h2>

                <form action="{{ route('admin_product_store') }}" method="post">

                    @include('admin.product._fields')

                    <button class="btn btn-primary" type="submit">Create</button>

                    <input type="hidden" value="{!! csrf_token() !!}" name="_token">

                </form>

            </div>
        </div>
    </section>

@stop
