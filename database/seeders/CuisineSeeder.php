<?php

namespace Database\Seeders;

use App\Models\Cuisine;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CuisineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cuisines = [
            'Indian',
            'Chinese',
            'Italian',
            'Mughlai',
            'South Indian',
            'Fast Food',
            'Bakery',
            'Continental',
            'Mexican',
            'Thai',
        ];

        foreach ($cuisines as $index => $name) {
            Cuisine::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'description' => "Delicious {$name} cuisine with authentic flavors and ingredients.",
                    'sort_order' => $index + 1,
                    'status' => 'active',
                ]
            );
        }
    }
}
