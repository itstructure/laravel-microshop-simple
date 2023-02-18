<?php

use App\Models\Product;

return [
    'modelClassName' => Product::class,
    'modelAdditionKeys' => [
        'title', 'catId', 'alias'
    ],
];