<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\Cuisine;
use App\Models\Food;
use App\Models\FoodCategory;
use App\Models\FoodVariant;
use App\Models\Restaurant;
use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BalajiRestaurantPizzaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $city = City::where('name', 'Lucknow')->first();
        $state = State::where('name', 'Uttar Pradesh')->first();
        $country = Country::where('name', 'India')->first();

        $owner = \App\Models\User::where('email', 'owner@restaurant.com')->first();
        $ownerId = $owner?->id ?? 1;

        // 1. Create or Find Bala Ji Restaurant
        $restaurant = Restaurant::where('restaurant_slug', 'bala-ji-restaurant')
            ->orWhere('restaurant_name', 'like', '%Bala%Ji%')
            ->first();

        if (!$restaurant) {
            $restaurant = Restaurant::create([
                'user_id' => $ownerId,
                'restaurant_name' => 'Bala Ji Restaurant',
                'restaurant_slug' => 'bala-ji-restaurant',
                'owner_name' => 'Bala Ji Partner',
                'email' => 'balaji@example.com',
                'mobile' => '9876543219',
                'address' => 'Shop No. 12, Hazratganj, Near Halwasiya Market, Lucknow',
                'city_id' => $city?->id,
                'state_id' => $state?->id,
                'country_id' => $country?->id,
                'postal_code' => '226001',
                'latitude' => 26.8467,
                'longitude' => 80.9462,
                'opening_time' => '10:00:00',
                'closing_time' => '23:30:00',
                'minimum_order_amount' => 199.00,
                'delivery_radius' => 15.0,
                'estimated_delivery_time' => 30,
                'commission_percentage' => 15.0,
                'dining_commission_percentage' => 10.0,
                'is_pure_veg' => false,
                'pet_friendly' => false,
                'outdoor_seating' => true,
                'serves_alcohol' => false,
                'logo' => 'front/assets/images/icons/brand1.png',
                'banner' => 'front/assets/images/product/vp-1.png',
                'description' => 'Famous for authentic handcrafted wood-fired and pan pizzas, giant monster slices, cheesy garlic breads, and super saver combos in Hazratganj, Lucknow.',
                'status' => 'active',
                'approval_status' => 'approved',
                'created_by' => 1,
            ]);
        } else {
            $restaurant->update([
                'user_id' => $ownerId,
                'status' => 'active',
                'approval_status' => 'approved',
                'city_id' => $city?->id ?? $restaurant->city_id,
            ]);
        }

        // Italian / Fast Food Cuisines
        $italian = Cuisine::firstOrCreate(['slug' => 'italian'], ['name' => 'Italian', 'status' => 'active', 'created_by' => 1]);
        $fastFood = Cuisine::firstOrCreate(['slug' => 'fast-food'], ['name' => 'Fast Food', 'status' => 'active', 'created_by' => 1]);
        $restaurant->cuisines()->syncWithoutDetaching([$italian->id, $fastFood->id]);

        // 2. Define the 10 Categories
        $categoriesData = [
            [
                'name' => 'Classic Pizzas For Classic Maniacs',
                'description' => 'All-time favorite classic crust pizzas with traditional toppings and rich mozzarella.',
                'items' => [
                    [
                        'name' => 'Classic Margherita Pizza',
                        'is_veg' => true,
                        'food_type' => 'veg',
                        'base_price' => 149,
                        'discount_price' => 129,
                        'desc' => 'Classic single cheese pizza with 100% real mozzarella cheese on fresh herb tomato sauce.',
                        'variants' => [
                            ['name' => 'Regular (7")', 'price' => 149, 'sale_price' => 129],
                            ['name' => 'Medium (10")', 'price' => 299, 'sale_price' => 269],
                            ['name' => 'Large (13")', 'price' => 499, 'sale_price' => 449],
                            ['name' => 'Cheese Burst Medium', 'price' => 379, 'sale_price' => 349],
                        ],
                    ],
                    [
                        'name' => 'Classic Double Cheese Margherita',
                        'is_veg' => true,
                        'food_type' => 'veg',
                        'base_price' => 199,
                        'discount_price' => 179,
                        'desc' => 'Loaded with extra liquid cheese and golden mozzarella cheese blend.',
                        'variants' => [
                            ['name' => 'Regular (7")', 'price' => 199, 'sale_price' => 179],
                            ['name' => 'Medium (10")', 'price' => 369, 'sale_price' => 329],
                            ['name' => 'Large (13")', 'price' => 569, 'sale_price' => 519],
                        ],
                    ],
                    [
                        'name' => 'Classic Farmhouse Delight Pizza',
                        'is_veg' => true,
                        'food_type' => 'veg',
                        'base_price' => 229,
                        'discount_price' => 199,
                        'desc' => 'Crunchy capsicum, juicy tomatoes, sweet golden corn, and earthy mushrooms.',
                        'variants' => [
                            ['name' => 'Regular (7")', 'price' => 229, 'sale_price' => 199],
                            ['name' => 'Medium (10")', 'price' => 399, 'sale_price' => 359],
                            ['name' => 'Large (13")', 'price' => 599, 'sale_price' => 549],
                        ],
                    ],
                    [
                        'name' => 'Classic Chicken Golden Delight',
                        'is_veg' => false,
                        'food_type' => 'non_veg',
                        'base_price' => 269,
                        'discount_price' => 239,
                        'desc' => 'Barbeque chicken, golden corn, and double cheese for meat lovers.',
                        'variants' => [
                            ['name' => 'Regular (7")', 'price' => 269, 'sale_price' => 239],
                            ['name' => 'Medium (10")', 'price' => 469, 'sale_price' => 419],
                            ['name' => 'Large (13")', 'price' => 679, 'sale_price' => 619],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Veg Pizza',
                'description' => 'Delicious handcrafted vegetarian pizzas with garden-fresh toppings.',
                'items' => [
                    [
                        'name' => 'Paneer Makhani Pizza',
                        'is_veg' => true,
                        'food_type' => 'veg',
                        'base_price' => 249,
                        'discount_price' => 219,
                        'desc' => 'Tender paneer chunks marinated in royal Makhani sauce with capsicum and onion.',
                        'variants' => [
                            ['name' => 'Regular (7")', 'price' => 249, 'sale_price' => 219],
                            ['name' => 'Medium (10")', 'price' => 429, 'sale_price' => 389],
                            ['name' => 'Large (13")', 'price' => 639, 'sale_price' => 579],
                            ['name' => 'Cheese Burst Medium', 'price' => 499, 'sale_price' => 459],
                        ],
                    ],
                    [
                        'name' => 'Veggie Supreme Extravaganza',
                        'is_veg' => true,
                        'food_type' => 'veg',
                        'base_price' => 279,
                        'discount_price' => 249,
                        'desc' => 'Black olives, crunchy bell peppers, jalapenos, mushrooms, onions, and sweet corn.',
                        'variants' => [
                            ['name' => 'Regular (7")', 'price' => 279, 'sale_price' => 249],
                            ['name' => 'Medium (10")', 'price' => 469, 'sale_price' => 419],
                            ['name' => 'Large (13")', 'price' => 699, 'sale_price' => 629],
                        ],
                    ],
                    [
                        'name' => 'Peri Peri Paneer Pizza',
                        'is_veg' => true,
                        'food_type' => 'veg',
                        'base_price' => 259,
                        'discount_price' => 229,
                        'desc' => 'Fiery peri-peri spiced cottage cheese cubes with red paprika and sliced capsicum.',
                        'variants' => [
                            ['name' => 'Regular (7")', 'price' => 259, 'sale_price' => 229],
                            ['name' => 'Medium (10")', 'price' => 449, 'sale_price' => 399],
                            ['name' => 'Large (13")', 'price' => 659, 'sale_price' => 599],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Non-veg Pizza',
                'description' => 'Succulent chicken and meat toppings baked on golden crispy crusts.',
                'items' => [
                    [
                        'name' => 'Chicken Tikka Makhani Pizza',
                        'is_veg' => false,
                        'food_type' => 'non_veg',
                        'base_price' => 289,
                        'discount_price' => 259,
                        'desc' => 'Charcoal roasted chicken tikka in buttery makhani gravy topped with red onions and cheese.',
                        'variants' => [
                            ['name' => 'Regular (7")', 'price' => 289, 'sale_price' => 259],
                            ['name' => 'Medium (10")', 'price' => 489, 'sale_price' => 439],
                            ['name' => 'Large (13")', 'price' => 729, 'sale_price' => 669],
                            ['name' => 'Cheese Burst Medium', 'price' => 559, 'sale_price' => 509],
                        ],
                    ],
                    [
                        'name' => 'Smoky BBQ Chicken Pizza',
                        'is_veg' => false,
                        'food_type' => 'non_veg',
                        'base_price' => 299,
                        'discount_price' => 269,
                        'desc' => 'Tender chicken tossed in sweet and smoky hickory BBQ sauce with bell peppers.',
                        'variants' => [
                            ['name' => 'Regular (7")', 'price' => 299, 'sale_price' => 269],
                            ['name' => 'Medium (10")', 'price' => 499, 'sale_price' => 449],
                            ['name' => 'Large (13")', 'price' => 749, 'sale_price' => 689],
                        ],
                    ],
                    [
                        'name' => 'Chicken Pepperoni & Sausage Pizza',
                        'is_veg' => false,
                        'food_type' => 'non_veg',
                        'base_price' => 319,
                        'discount_price' => 289,
                        'desc' => 'Authentic chicken pepperoni slices, spiced sausages, and loads of molten mozzarella.',
                        'variants' => [
                            ['name' => 'Regular (7")', 'price' => 319, 'sale_price' => 289],
                            ['name' => 'Medium (10")', 'price' => 529, 'sale_price' => 479],
                            ['name' => 'Large (13")', 'price' => 789, 'sale_price' => 719],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Personal Pizza Slice',
                'description' => 'Giant single slice monster pizzas, crafted for quick solo cravings.',
                'items' => [
                    [
                        'name' => 'Cheesy Margherita Monster Slice',
                        'is_veg' => true,
                        'food_type' => 'veg',
                        'base_price' => 99,
                        'discount_price' => 89,
                        'desc' => 'One giant slice of molten cheese classic Margherita pizza.',
                        'variants' => [
                            ['name' => 'Single Giant Slice', 'price' => 99, 'sale_price' => 89],
                            ['name' => 'Slice + Coke Combo', 'price' => 149, 'sale_price' => 129],
                        ],
                    ],
                    [
                        'name' => 'Paneer Tikka Personal Giant Slice',
                        'is_veg' => true,
                        'food_type' => 'veg',
                        'base_price' => 129,
                        'discount_price' => 119,
                        'desc' => 'Extra large slice loaded with spiced tandoori paneer and crunchy capsicum.',
                        'variants' => [
                            ['name' => 'Single Giant Slice', 'price' => 129, 'sale_price' => 119],
                            ['name' => 'Slice + Fries Combo', 'price' => 199, 'sale_price' => 169],
                        ],
                    ],
                    [
                        'name' => 'Chicken Tikka Monster Giant Slice',
                        'is_veg' => false,
                        'food_type' => 'non_veg',
                        'base_price' => 149,
                        'discount_price' => 139,
                        'desc' => 'Big NY-style fold slice packed with spiced roast chicken and cheddar cheese blend.',
                        'variants' => [
                            ['name' => 'Single Giant Slice', 'price' => 149, 'sale_price' => 139],
                            ['name' => 'Slice + Coke Combo', 'price' => 189, 'sale_price' => 169],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Super Saver Combos',
                'description' => 'Best value money-saving combo packs for friends and family.',
                'items' => [
                    [
                        'name' => '2 Medium Veg Pizzas + Garlic Bread Combo',
                        'is_veg' => true,
                        'food_type' => 'veg',
                        'base_price' => 699,
                        'discount_price' => 599,
                        'desc' => 'Any 2 Medium Veg Pizzas + 1 Stuffed Garlic Bread + 2 Pepsi 250ml.',
                        'variants' => [
                            ['name' => 'Standard Combo', 'price' => 699, 'sale_price' => 599],
                            ['name' => 'Cheese Burst Upgrade', 'price' => 849, 'sale_price' => 729],
                        ],
                    ],
                    [
                        'name' => '2 Medium Non-Veg Pizzas + Wings Combo',
                        'is_veg' => false,
                        'food_type' => 'non_veg',
                        'base_price' => 899,
                        'discount_price' => 749,
                        'desc' => '2 Medium Non-Veg Pizzas + 4 pcs BBQ Chicken Wings + 2 Pepsi cans.',
                        'variants' => [
                            ['name' => 'Standard Combo', 'price' => 899, 'sale_price' => 749],
                            ['name' => 'Cheese Burst Upgrade', 'price' => 1049, 'sale_price' => 899],
                        ],
                    ],
                    [
                        'name' => 'Pizza Party Mega Combo',
                        'is_veg' => true,
                        'food_type' => 'veg',
                        'base_price' => 1199,
                        'discount_price' => 999,
                        'desc' => '2 Large Pizzas + 2 Garlic Breads + 1 Choco Lava Cake + 1.25L Soft Drink.',
                        'variants' => [
                            ['name' => 'Party Pack (Serves 5-6)', 'price' => 1199, 'sale_price' => 999],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Sides',
                'description' => 'Crispy sides, stuffed garlic breads, dips, and finger foods.',
                'items' => [
                    [
                        'name' => 'Stuffed Cheesy Garlic Bread',
                        'is_veg' => true,
                        'food_type' => 'veg',
                        'base_price' => 149,
                        'discount_price' => 129,
                        'desc' => 'Freshly baked buttery garlic bread filled with molten cheese, corn and jalapenos.',
                        'variants' => [
                            ['name' => 'Classic Corn & Jalapeno', 'price' => 149, 'sale_price' => 129],
                            ['name' => 'Paneer Tikka Stuffed', 'price' => 179, 'sale_price' => 159],
                            ['name' => 'Chicken Tikka Stuffed', 'price' => 199, 'sale_price' => 179],
                        ],
                    ],
                    [
                        'name' => 'Peri Peri French Fries',
                        'is_veg' => true,
                        'food_type' => 'veg',
                        'base_price' => 119,
                        'discount_price' => 99,
                        'desc' => 'Golden crispy potato fries dusted with tangy and spicy African Peri-Peri mix.',
                        'variants' => [
                            ['name' => 'Medium', 'price' => 119, 'sale_price' => 99],
                            ['name' => 'Large with Cheese Dip', 'price' => 169, 'sale_price' => 149],
                        ],
                    ],
                    [
                        'name' => 'Crispy BBQ Chicken Wings (6 Pcs)',
                        'is_veg' => false,
                        'food_type' => 'non_veg',
                        'base_price' => 229,
                        'discount_price' => 199,
                        'desc' => 'Juicy chicken wings coated in rich BBQ sauce and oven baked till crispy.',
                        'variants' => [
                            ['name' => '6 Pieces', 'price' => 229, 'sale_price' => 199],
                            ['name' => '12 Pieces Sharing Box', 'price' => 399, 'sale_price' => 349],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Meal Combos',
                'description' => 'Complete lunch and dinner meal boxes with beverage.',
                'items' => [
                    [
                        'name' => 'Solo Pizza Meal Box',
                        'is_veg' => true,
                        'food_type' => 'veg',
                        'base_price' => 249,
                        'discount_price' => 219,
                        'desc' => '1 Personal Pizza + 2 pcs Garlic Bread + 1 Cold Drink 250ml.',
                        'variants' => [
                            ['name' => 'Veg Meal Box', 'price' => 249, 'sale_price' => 219],
                            ['name' => 'Non-Veg Meal Box', 'price' => 299, 'sale_price' => 269],
                        ],
                    ],
                    [
                        'name' => 'Duo Meal Box For Two',
                        'is_veg' => true,
                        'food_type' => 'veg',
                        'base_price' => 499,
                        'discount_price' => 449,
                        'desc' => '1 Medium Pizza + 1 Stuffed Garlic Bread + 2 Cold Drinks.',
                        'variants' => [
                            ['name' => 'Veg Duo', 'price' => 499, 'sale_price' => 449],
                            ['name' => 'Non-Veg Duo', 'price' => 599, 'sale_price' => 539],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Four Course Meal',
                'description' => 'Full dining experience with soup, starter, gourmet pizza, and dessert.',
                'items' => [
                    [
                        'name' => '4 Course Veg Italian Feast',
                        'is_veg' => true,
                        'food_type' => 'veg',
                        'base_price' => 549,
                        'discount_price' => 479,
                        'desc' => 'Tomato Basil Soup + Stuffed Garlic Bread + 1 Medium Veg Pizza + Choco Lava Cake.',
                        'variants' => [
                            ['name' => 'Standard 4-Course Veg', 'price' => 549, 'sale_price' => 479],
                        ],
                    ],
                    [
                        'name' => '4 Course Non-Veg Gourmet Feast',
                        'is_veg' => false,
                        'food_type' => 'non_veg',
                        'base_price' => 649,
                        'discount_price' => 579,
                        'desc' => 'Chicken Soup + 4 pcs Wings + 1 Medium Non-Veg Pizza + Choco Lava Cake.',
                        'variants' => [
                            ['name' => 'Standard 4-Course Non-Veg', 'price' => 649, 'sale_price' => 579],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Desserts And Beverages',
                'description' => 'Sweet treats, molten cakes, refreshing sodas and thick shakes.',
                'items' => [
                    [
                        'name' => 'Choco Lava Cake',
                        'is_veg' => true,
                        'food_type' => 'veg',
                        'base_price' => 109,
                        'discount_price' => 99,
                        'desc' => 'Warm chocolate cake with a rich molten liquid chocolate center.',
                        'variants' => [
                            ['name' => 'Single Piece', 'price' => 109, 'sale_price' => 99],
                            ['name' => 'Pack of 2', 'price' => 199, 'sale_price' => 179],
                        ],
                    ],
                    [
                        'name' => 'Warm Walnut Brownie',
                        'is_veg' => true,
                        'food_type' => 'veg',
                        'base_price' => 129,
                        'discount_price' => 109,
                        'desc' => 'Gooey dark chocolate brownie with crunchy toasted walnuts.',
                        'variants' => [
                            ['name' => 'Single Brownie', 'price' => 129, 'sale_price' => 109],
                        ],
                    ],
                    [
                        'name' => 'Coca-Cola Can (330ml)',
                        'is_veg' => true,
                        'food_type' => 'veg',
                        'base_price' => 45,
                        'discount_price' => 40,
                        'desc' => 'Chilled refreshing Coca-Cola can.',
                        'variants' => [
                            ['name' => 'Can 330ml', 'price' => 45, 'sale_price' => 40],
                            ['name' => 'Bottle 750ml', 'price' => 60, 'sale_price' => 55],
                        ],
                    ],
                    [
                        'name' => 'Belgian Chocolate Thick Shake',
                        'is_veg' => true,
                        'food_type' => 'veg',
                        'base_price' => 159,
                        'discount_price' => 139,
                        'desc' => 'Creamy thick shake blended with rich Belgian chocolate ice cream.',
                        'variants' => [
                            ['name' => '300ml Glass', 'price' => 159, 'sale_price' => 139],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Dotd',
                'description' => 'Deal Of The Day! Exclusive flash discounts and special pizza offers today.',
                'items' => [
                    [
                        'name' => '[DOTD] Buy 1 Medium Pizza Get 1 Free',
                        'is_veg' => true,
                        'food_type' => 'veg',
                        'base_price' => 499,
                        'discount_price' => 399,
                        'desc' => 'Today special BOGO deal: Order 1 Medium Gourmet Pizza and get 1 Classic Margherita Free!',
                        'variants' => [
                            ['name' => 'BOGO Medium Veg Deal', 'price' => 499, 'sale_price' => 399],
                            ['name' => 'BOGO Medium Non-Veg Deal', 'price' => 599, 'sale_price' => 499],
                        ],
                    ],
                    [
                        'name' => '[DOTD] Monster Slice Pizza + Coke @ Flat 149',
                        'is_veg' => true,
                        'food_type' => 'veg',
                        'base_price' => 189,
                        'discount_price' => 149,
                        'desc' => 'Super flash deal: 1 Huge Monster Slice of your choice + 1 Chilled Coke 250ml.',
                        'variants' => [
                            ['name' => 'Veg Monster Combo', 'price' => 189, 'sale_price' => 149],
                            ['name' => 'Non-Veg Monster Combo', 'price' => 219, 'sale_price' => 169],
                        ],
                    ],
                    [
                        'name' => '[DOTD] Cheesy Garlic Bread @ 49 on Orders Above 299',
                        'is_veg' => true,
                        'food_type' => 'veg',
                        'base_price' => 149,
                        'discount_price' => 49,
                        'desc' => 'Daily special add-on discount: Get freshly baked stuffed garlic bread for just ₹49!',
                        'variants' => [
                            ['name' => 'Special Add-on', 'price' => 149, 'sale_price' => 49],
                        ],
                    ],
                ],
            ],
        ];

        // 3. Insert Categories, Foods, and Variants
        foreach ($categoriesData as $catIndex => $cData) {
            $categorySlug = Str::slug($cData['name']) . '-' . $restaurant->id;
            $category = FoodCategory::updateOrCreate(
                [
                    'restaurant_id' => $restaurant->id,
                    'name' => $cData['name'],
                ],
                [
                    'slug' => $categorySlug,
                    'description' => $cData['description'],
                    'sort_order' => $catIndex + 1,
                    'status' => 'active',
                    'created_by' => 1,
                ]
            );

            foreach ($cData['items'] as $itemIndex => $item) {
                $foodSlug = Str::slug($item['name']) . '-' . $restaurant->id;
                $food = Food::updateOrCreate(
                    [
                        'restaurant_id' => $restaurant->id,
                        'food_category_id' => $category->id,
                        'name' => $item['name'],
                    ],
                    [
                        'slug' => $foodSlug,
                        'short_description' => $item['desc'],
                        'description' => $item['desc'],
                        'sku' => 'BALA-' . strtoupper(Str::random(6)),
                        'food_type' => $item['food_type'],
                        'is_veg' => $item['is_veg'],
                        'is_featured' => $catIndex === 0 || $catIndex === 9,
                        'is_recommended' => true,
                        'is_spicy' => str_contains(strtolower($item['name']), 'spicy') || str_contains(strtolower($item['name']), 'peri'),
                        'preparation_time' => 20,
                        'base_price' => $item['base_price'],
                        'discount_price' => $item['discount_price'] ?? null,
                        'tax_percentage' => 5.00,
                        'status' => 'active',
                        'sort_order' => $itemIndex + 1,
                        'created_by' => 1,
                    ]
                );

                $food->cuisines()->syncWithoutDetaching([$italian->id, $fastFood->id]);

                // Create Variants
                if (!empty($item['variants'])) {
                    foreach ($item['variants'] as $vIndex => $v) {
                        FoodVariant::updateOrCreate(
                            [
                                'food_id' => $food->id,
                                'variant_name' => $v['name'],
                            ],
                            [
                                'sku' => $food->sku . '-V' . ($vIndex + 1),
                                'price' => $v['price'],
                                'sale_price' => $v['sale_price'] ?? null,
                                'cost_price' => ($v['price'] * 0.4),
                                'serving_size' => '1-2 persons',
                                'preparation_time' => 20,
                                'status' => 'active',
                                'sort_order' => $vIndex + 1,
                                'created_by' => 1,
                            ]
                        );
                    }
                }
            }
        }
    }
}
