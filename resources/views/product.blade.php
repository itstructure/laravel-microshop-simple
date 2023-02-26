@extends('layouts.shop')

@section('body')

    <div class="row">
        <div class="col-12">
            <div class="media product-block">
                <img src="/images/product{{ $model->category_id }}.jpg" class="mr-3" alt="{{ $model->title }}">
                <div class="media-body">
                    <h5 class="mt-0">{{ $model->title }}</h5>
                    <div>{{ $model->description }}</div>
                    <div class="mt-4">
                        <a href="javascript:void(0)" class="btn btn-secondary" onclick="window.top_card_adapter.putToCard('{{ $model->id }}')">Put to card</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
