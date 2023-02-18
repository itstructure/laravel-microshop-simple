@extends('layouts.app')

@section('content')

    @if(isset($totalAmount))
        <script>
            window.init_top_card_props = {
                totalAmount: parseInt('{{ $totalAmount }}')
            };
        </script>

        @if(isset($cardProducts) && isset($cardCounts))
            <script>
                window.init_order_card_props = {
                    cardProducts: {!! json_encode($cardProducts) !!},
                    cardCounts: {!! json_encode($cardCounts) !!},
                    totalAmount: parseInt('{{ $totalAmount }}')
                };
            </script>
        @endif
    @endif

    <div class="row mx-0">
        <div class="col-xs-12 col-md-3 col-lg-3 col-xl-2 offset-lg-1 mb-3">
            <div class="card menu">
                <ul class="list-group list-group-flush">
                    @foreach($categories as $cat)
                        <li class="list-group-item p-0 @if(isset($category) && $category->id == $cat->id) selected @endif">
                            <a href="{{ route('category_products', ['alias' => $cat->alias]) }}" class="d-block py-4 px-3">{{ $cat->title }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-xs-12 col-md-9 col-lg-7 col-xl-8">
            @yield('body')
        </div>
    </div>

@endsection
