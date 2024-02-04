@section('title', 'Orders')
@extends('adminlte::page')
@section('content')

    @php
    $gridData = [
        'dataProvider' => $dataProvider,
        'paginatorOptions' => [
            'pageName' => 'p',
            'onEachSide' => 1
        ],
        'rowsPerPage' => 5,
        'title' => 'Orders',
        'strictFilters' => false,
        'rowsFormAction' => route('admin_order_delete'),
        'columnFields' => [
            [
                'attribute' => 'id',
                'filter' => false,
                'htmlAttributes' => [
                    'width' => '5%',
                ],
            ],
            [
                'label' => 'User name',
                'attribute' => 'user_name'
            ],
            [
                'label' => 'Email',
                'attribute' => 'user_email'
            ],
            [
                'label' => 'Products',
                'value' => function ($row) {
                    $html = '';
                    $products = $row->products;
                    foreach ($products as $product) {
                        $html .= '<p>'.$product->title.' | $'.$product->price.' | '.$product->pivot->count.' pieces</p>';
                    }
                    return $html;
                },
                'format' => 'html',
                'filter' => false,
            ],
            [
                'label' => 'Actual amount, $',
                'value' => function ($row) {
                    $amount = 0;
                    $products = $row->products;
                    foreach ($products as $product) {
                        $amount += ($product->price * $product->pivot->count);
                    }
                    return $amount;
                },
                'filter' => false,
            ],
            [
                'label' => 'Created',
                'attribute' => 'created_at'
            ],
            [
                'class' => Itstructure\GridView\Columns\CheckboxColumn::class,
                'field' => 'items',
                'attribute' => 'id'
            ],
        ],
    ];
    @endphp

    @gridView($gridData)

@stop