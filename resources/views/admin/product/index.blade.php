@section('title', 'Products')
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
        'title' => 'Products',
        'strictFilters' => false,
        'rowsFormAction' => route('admin_product_delete'),
        'columnFields' => [
            [
                'attribute' => 'id',
                'filter' => false,
                'htmlAttributes' => [
                    'width' => '5%',
                ],
            ],
            [
                'label' => 'Title',
                'attribute' => 'title'
            ],
            [
                'label' => 'Alias',
                'attribute' => 'alias'
            ],
            [
                'label' => 'Price, $',
                'attribute' => 'price'
            ],
            [
                'label' => 'Category',
                'value' => function ($row) {
                    return '<a href="'.route('admin_category_view', ['id' => $row->category->id]).'">'.$row->category->title.'</a>';
                },
                'format' => 'html',
                'filter' => false,
            ],
            [
                'label' => 'Created',
                'attribute' => 'created_at'
            ],
            [
                'class' => Itstructure\GridView\Columns\ActionColumn::class,
                'actionTypes' => [
                    'view' => function ($data) {
                        return route('admin_product_view', ['id' => $data->id]);
                    },
                    'edit' => function ($data) {
                        return route('admin_product_edit', ['id' => $data->id]);
                    },
                ],
                'htmlAttributes' => [
                    'width' => '120'
                ]
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