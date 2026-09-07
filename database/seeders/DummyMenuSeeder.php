<?php

namespace Database\Seeders;

use App\Models\Cuisine;
use App\Models\Food;
use App\Models\FoodCategory;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DummyMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $restaurant = Restaurant::first();

        if (!$restaurant) {
            $this->command->error('No restaurant found. Run AdminSeeder first.');
            return;
        }

        $ownerId = $restaurant->user_id ?? 1;

        // ---------------------------------------------------------------
        // 1. Cuisines (10)
        // ---------------------------------------------------------------
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

        $cuisineIds = [];
        foreach ($cuisines as $index => $name) {
            $cuisine = Cuisine::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'description' => "Delicious {$name} cuisine with authentic flavors and ingredients.",
                    'sort_order' => $index + 1,
                    'status' => 'active',
                    'created_by' => $ownerId,
                ]
            );
            $cuisineIds[] = $cuisine->id;
        }

        // ---------------------------------------------------------------
        // 2. Food Categories (10)
        // ---------------------------------------------------------------
        $categories = [
            ['name' => 'Starters', 'description' => 'Tasty appetizers and starters.'],
            ['name' => 'Main Course', 'description' => 'Hearty main course dishes.'],
            ['name' => 'Biryanis & Rice', 'description' => 'Fragrant biryanis and rice preparations.'],
            ['name' => 'Breads', 'description' => 'Fresh tandoor and tawa breads.'],
            ['name' => 'Soups', 'description' => 'Warm and comforting soups.'],
            ['name' => 'Salads', 'description' => 'Fresh and healthy salads.'],
            ['name' => 'Desserts', 'description' => 'Sweet treats and desserts.'],
            ['name' => 'Beverages', 'description' => 'Cooling drinks and beverages.'],
            ['name' => 'Fast Food', 'description' => 'Quick bites and fast food.'],
            ['name' => 'South Indian', 'description' => 'Authentic South Indian delicacies.'],
        ];

        $categoryIds = [];
        foreach ($categories as $index => $cat) {
            $category = FoodCategory::updateOrCreate(
                [
                    'restaurant_id' => $restaurant->id,
                    'slug' => Str::slug($cat['name']),
                ],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                    'sort_order' => $index + 1,
                    'status' => 'active',
                    'created_by' => $ownerId,
                ]
            );
            $categoryIds[] = $category->id;
        }

        // ---------------------------------------------------------------
        // 3. Foods (10)
        // ---------------------------------------------------------------
        $foods = [
            ['name' => 'Butter Chicken', 'category' => 1, 'cuisines' => [0], 'food_type' => 'non_veg', 'is_veg' => false, 'price' => 320, 'discount' => 299, 'prep' => 25, 'spicy' => false, 'featured' => true, 'desc' => 'Tender chicken cooked in rich creamy tomato butter gravy.'],
            ['name' => 'Chicken Biryani', 'category' => 2, 'cuisines' => [0, 3], 'food_type' => 'non_veg', 'is_veg' => false, 'price' => 280, 'discount' => 249, 'prep' => 30, 'spicy' => true, 'featured' => true, 'desc' => 'Fragrant basmati rice layered with spiced chicken and saffron.'],
            ['name' => 'Paneer Tikka', 'category' => 0, 'cuisines' => [0], 'food_type' => 'veg', 'is_veg' => true, 'price' => 220, 'discount' => 199, 'prep' => 20, 'spicy' => false, 'featured' => false, 'desc' => 'Char-grilled cottage cheese cubes marinated in yogurt and spices.'],
            ['name' => 'Veg Fried Rice', 'category' => 8, 'cuisines' => [1], 'food_type' => 'veg', 'is_veg' => true, 'price' => 180, 'discount' => null, 'prep' => 15, 'spicy' => false, 'featured' => false, 'desc' => 'Wok-tossed rice with crunchy vegetables and soy sauce.'],
            ['name' => 'Margherita Pizza', 'category' => 8, 'cuisines' => [2], 'food_type' => 'veg', 'is_veg' => true, 'price' => 250, 'discount' => 229, 'prep' => 20, 'spicy' => false, 'featured' => false, 'desc' => 'Wood-fired pizza topped with fresh mozzarella, basil and tomato.'],
            ['name' => 'Masala Dosa', 'category' => 9, 'cuisines' => [4], 'food_type' => 'veg', 'is_veg' => true, 'price' => 120, 'discount' => null, 'prep' => 15, 'spicy' => false, 'featured' => false, 'desc' => 'Crispy golden dosa served with spiced potato filling and chutneys.'],
            ['name' => 'Dal Makhani', 'category' => 1, 'cuisines' => [0], 'food_type' => 'veg', 'is_veg' => true, 'price' => 210, 'discount' => 189, 'prep' => 20, 'spicy' => false, 'featured' => false, 'desc' => 'Slow-cooked black lentils simmered in butter and cream.'],
            ['name' => 'Garlic Naan', 'category' => 3, 'cuisines' => [0], 'food_type' => 'veg', 'is_veg' => true, 'price' => 60, 'discount' => null, 'prep' => 10, 'spicy' => false, 'featured' => false, 'desc' => 'Soft tandoor-baked naan topped with garlic and coriander.'],
            ['name' => 'Chocolate Brownie', 'category' => 6, 'cuisines' => [6], 'food_type' => 'veg', 'is_veg' => true, 'price' => 150, 'discount' => 129, 'prep' => 10, 'spicy' => false, 'featured' => true, 'desc' => 'Fudgy chocolate brownie served warm with a scoop of vanilla ice cream.'],
            ['name' => 'Fresh Lime Soda', 'category' => 7, 'cuisines' => [5], 'food_type' => 'veg', 'is_veg' => true, 'price' => 70, 'discount' => null, 'prep' => 5, 'spicy' => false, 'featured' => false, 'desc' => 'Refreshing lime soda sweetened and served chilled.'],
        ];

        foreach ($foods as $index => $food) {
            $item = Food::updateOrCreate(
                [
                    'restaurant_id' => $restaurant->id,
                    'slug' => Str::slug($food['name']),
                ],
                [
                    'food_category_id' => $categoryIds[$food['category']],
                    'name' => $food['name'],
                    'short_description' => Str::limit($food['desc'], 60),
                    'description' => $food['desc'],
                    'sku' => 'SKU-' . strtoupper(Str::random(6)),
                    'food_type' => $food['food_type'],
                    'is_veg' => $food['is_veg'],
                    'is_featured' => $food['featured'],
                    'is_recommended' => ($index % 3 === 0),
                    'is_spicy' => $food['spicy'],
                    'preparation_time' => $food['prep'],
                    'base_price' => $food['price'],
                    'discount_price' => $food['discount'],
                    'tax_percentage' => 5,
                    'status' => 'active',
                    'sort_order' => $index + 1,
                    'created_by' => $ownerId,
                ]
            );

            // Attach cuisines
            $attachIds = array_map(fn ($i) => $cuisineIds[$i], $food['cuisines']);
            $item->cuisines()->sync($attachIds);
        }

        // ---------------------------------------------------------------
        // 4. Food Variants (dummy, per food keyed by slug)
        // ---------------------------------------------------------------
        $variantsBySlug = [
            'butter-chicken' => [ // Butter Chicken
                ['name' => 'Half', 'weight' => 250, 'prep' => 25, 'price' => 220, 'sale' => 199],
                ['name' => 'Full', 'weight' => 500, 'prep' => 30, 'price' => 320, 'sale' => 299],
            ],
            'chicken-biryani' => [ // Chicken Biryani
                ['name' => 'Regular', 'weight' => 350, 'prep' => 25, 'price' => 199, 'sale' => 179],
                ['name' => 'Large', 'weight' => 600, 'prep' => 35, 'price' => 280, 'sale' => 249],
                ['name' => 'Family Pack', 'weight' => 1000, 'prep' => 45, 'price' => 520, 'sale' => 479],
            ],
            'paneer-tikka' => [ // Paneer Tikka
                ['name' => '6 Pieces', 'weight' => 180, 'prep' => 20, 'price' => 180, 'sale' => 159],
                ['name' => '12 Pieces', 'weight' => 360, 'prep' => 25, 'price' => 220, 'sale' => 199],
            ],
            'veg-fried-rice' => [ // Veg Fried Rice
                ['name' => 'Half', 'weight' => 250, 'prep' => 15, 'price' => 120, 'sale' => null],
                ['name' => 'Full', 'weight' => 500, 'prep' => 20, 'price' => 180, 'sale' => null],
            ],
            'margherita-pizza' => [ // Margherita Pizza
                ['name' => 'Medium', 'weight' => 350, 'prep' => 20, 'price' => 199, 'sale' => 179],
                ['name' => 'Large', 'weight' => 600, 'prep' => 25, 'price' => 250, 'sale' => 229],
            ],
            'masala-dosa' => [ // Masala Dosa
                ['name' => 'Plain Masala', 'weight' => 200, 'prep' => 15, 'price' => 90, 'sale' => null],
                ['name' => 'Butter Masala', 'weight' => 220, 'prep' => 15, 'price' => 120, 'sale' => null],
                ['name' => 'Cheese Masala', 'weight' => 250, 'prep' => 18, 'price' => 150, 'sale' => null],
            ],
            'dal-makhani' => [ // Dal Makhani
                ['name' => 'Half', 'weight' => 250, 'prep' => 15, 'price' => 150, 'sale' => 135],
                ['name' => 'Full', 'weight' => 500, 'prep' => 20, 'price' => 210, 'sale' => 189],
            ],
            'garlic-naan' => [ // Garlic Naan
                ['name' => 'Single', 'weight' => 100, 'prep' => 10, 'price' => 40, 'sale' => null],
                ['name' => 'Combo (2 pcs)', 'weight' => 200, 'prep' => 12, 'price' => 60, 'sale' => null],
            ],
            'chocolate-brownie' => [ // Chocolate Brownie
                ['name' => 'Single', 'weight' => 120, 'prep' => 10, 'price' => 110, 'sale' => 99],
                ['name' => 'With Ice Cream', 'weight' => 150, 'prep' => 12, 'price' => 150, 'sale' => 129],
            ],
            'fresh-lime-soda' => [ // Fresh Lime Soda
                ['name' => 'Glass', 'weight' => 250, 'prep' => 5, 'price' => 50, 'sale' => null],
                ['name' => 'Jug (1 Litre)', 'weight' => 1000, 'prep' => 8, 'price' => 70, 'sale' => null],
            ],
        ];

        $foodItems = Food::where('restaurant_id', $restaurant->id)->get();

        $variantCount = 0;
        foreach ($foodItems as $foodItem) {
            $variants = $variantsBySlug[$foodItem->slug] ?? null;

            if ($variants === null) {
                continue;
            }

            foreach ($variants as $vIndex => $variant) {
                \App\Models\FoodVariant::updateOrCreate(
                    [
                        'food_id' => $foodItem->id,
                        'variant_name' => $variant['name'],
                    ],
                    [
                        'sku' => 'VAR-' . strtoupper(Str::random(6)),
                        'price' => $variant['price'],
                        'sale_price' => $variant['sale'],
                        'cost_price' => round($variant['price'] * 0.6, 2),
                        'weight' => $variant['weight'],
                        'weight_unit' => 'g',
                        'serving_size' => $variant['name'],
                        'preparation_time' => $variant['prep'],
                        'status' => 'active',
                        'sort_order' => $vIndex + 1,
                        'created_by' => $ownerId,
                    ]
                );
                $variantCount++;
            }
        }

        $this->command->info("Dummy data seeded: 10 cuisines, 10 food categories, 10 foods, {$variantCount} food variants.");
    }
}
