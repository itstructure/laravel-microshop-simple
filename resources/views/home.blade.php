@extends('layouts.shop')

@section('body')

    <div class="row">
        @foreach($products as $product)
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card my-2 product-block">
                    <div class="product-logo">
                        <a href="{{ route('product', ['alias' => $product->alias]) }}" target="_self">
                            <img src="/images/product{{ $product->category_id }}.jpg">
                        </a>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->title }}</h5>
                        <p class="card-text">{{ $product->description }}</p>
                    </div>
                    <div class="card-footer p-2">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-xl-12 d-flex align-items-center justify-content-center">
                                Price ${{ $product->price }}
                            </div>
                            <div class="col-12 col-sm-6 col-xl-12">
                                <a href="javascript:void(0)" class="btn btn-secondary card-link" onclick="window.top_card_adapter.putToCard('{{ $product->id }}')">Put to card</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col-12">
            {{ $products->links() }}
        </div>
    </div>

@endsection
