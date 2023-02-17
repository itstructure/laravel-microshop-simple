<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

/**
 * Class ProductSeeder
 */
class ProductSeeder extends Seeder
{
    const MIN_PRICE = 5;

    const MAX_PRICE = 30;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::insert($this->generateProductEntries());
    }

    /**
     * @return array
     */
    private function generateProductEntries()
    {
        $entries = [];

        for ($i=1; $i <= DatabaseSeeder::COUNT_OF_CATEGORIES; $i++) {
            for ($j=1; $j <= DatabaseSeeder::PRODUCTS_IN_CATEGORY; $j++) {
                $entries[] = [
                    'title' => 'Product '.$i.'-'.$j,
                    'alias' => 'product-'.$i.'-'.$j,
                    'description' => 'Description text '.$i.'-'.$j,
                    'price' => rand(self::MIN_PRICE, self::MAX_PRICE),
                    'category_id' => $i,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        return $entries;
    }
}
