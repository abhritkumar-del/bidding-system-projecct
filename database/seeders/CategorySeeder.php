<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Mobile Phones',
                'slug' => 'mobile-phones',
                'description' => 'Smartphones, feature phones, and accessories',
                'icon' => 'fa-mobile-alt',
                'order' => 1,
                'is_active' => true
            ],
            [
                'name' => 'Laptops & Computers',
                'slug' => 'laptops-computers',
                'description' => 'Laptops, desktops, and computer accessories',
                'icon' => 'fa-laptop',
                'order' => 2,
                'is_active' => true
            ],
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'TVs, cameras, audio systems, and more',
                'icon' => 'fa-tv',
                'order' => 3,
                'is_active' => true
            ],
            [
                'name' => 'Home Appliances',
                'slug' => 'home-appliances',
                'description' => 'Refrigerators, washing machines, ACs, etc.',
                'icon' => 'fa-blender',
                'order' => 4,
                'is_active' => true
            ],
            [
                'name' => 'Fashion & Accessories',
                'slug' => 'fashion-accessories',
                'description' => 'Clothing, watches, jewelry, and more',
                'icon' => 'fa-tshirt',
                'order' => 5,
                'is_active' => true
            ],
            [
                'name' => 'Vehicles',
                'slug' => 'vehicles',
                'description' => 'Cars, bikes, and vehicle accessories',
                'icon' => 'fa-car',
                'order' => 6,
                'is_active' => true
            ],
            [
                'name' => 'Furniture',
                'slug' => 'furniture',
                'description' => 'Home and office furniture',
                'icon' => 'fa-couch',
                'order' => 7,
                'is_active' => true
            ],
            [
                'name' => 'Books & Media',
                'slug' => 'books-media',
                'description' => 'Books, DVDs, games, and collectibles',
                'icon' => 'fa-book',
                'order' => 8,
                'is_active' => true
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}