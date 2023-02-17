<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

/**
 * Class CategorySeeder
 */
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::insert($this->generateCategoryEntries());
    }

    /**
     * @return array
     */
    private function generateCategoryEntries()
    {
        $entries = [];

        for ($i=1; $i <= DatabaseSeeder::COUNT_OF_CATEGORIES; $i++) {
            $entries[] = [
                'title' => 'Category '.$i,
                'alias' => 'category-'.$i,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        return $entries;
    }
}
