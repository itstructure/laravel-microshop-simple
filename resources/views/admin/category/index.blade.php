@section('title', 'Categories')
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
        'title' => 'Categories',
        'strictFilters' => false,
        'rowsFormAction' => route('admin_category_delete'),
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
                'label' => 'Created',
                'attribute' => 'created_at'
            ],
            [
                'class' => Itstructure\GridView\Columns\ActionColumn::class,
                'actionTypes' => [
                    'view' => function ($data) {
                        return route('admin_category_view', ['id' => $data->id]);
                    },
                    'edit' => function ($data) {
                        return route('admin_category_edit', ['id' => $data->id]);
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