@section('title', $title)
@extends('adminlte::page')
@section('content')

    <section class="content container-fluid">
        <div class="row">
            <div class="col-12 mt-1">
                <iframe src="{{ $fileManagerRoute }}" frameborder="0" style="width: 100%; min-height: 800px"></iframe>
            </div>
        </div>
    </section>

@stop
