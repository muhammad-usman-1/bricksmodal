<?php

namespace Database\Seeders;

use App\Models\Outfit;
use Illuminate\Database\Seeder;

class OutfitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $outfits = [
            // Male outfits
            ['name' => 'White Formal Shirt', 'category' => 'male', 'sub_category' => 'top', 'sort_order' => 1, 'image' => '/images/avatars/3d/shirt.png'],
            ['name' => 'Black Polo', 'category' => 'male', 'sub_category' => 'top', 'sort_order' => 2, 'image' => '/images/avatars/3d/shirt.png'],
            ['name' => 'Blue Denim Jeans', 'category' => 'male', 'sub_category' => 'bottom', 'sort_order' => 3, 'image' => '/images/avatars/3d/jeans.png'],
            ['name' => 'Khaki Chinos', 'category' => 'male', 'sub_category' => 'bottom', 'sort_order' => 4, 'image' => '/images/avatars/3d/jeans.png'],
            ['name' => 'Traditional Thobe', 'category' => 'male', 'sub_category' => 'traditional', 'sort_order' => 5, 'image' => '/images/outfit-icons/traditional_male.png'],

            // Female outfits
            ['name' => 'Silk Blouse', 'category' => 'female', 'sub_category' => 'top', 'sort_order' => 1, 'image' => '/images/avatars/3d/shirt.png'],
            ['name' => 'Cropped Top', 'category' => 'female', 'sub_category' => 'top', 'sort_order' => 2, 'image' => '/images/avatars/3d/shirt.png'],
            ['name' => 'Black Pencil Skirt', 'category' => 'female', 'sub_category' => 'bottom', 'sort_order' => 3, 'image' => '/images/avatars/3d/jeans.png'],
            ['name' => 'High-Waist Trousers', 'category' => 'female', 'sub_category' => 'bottom', 'sort_order' => 4, 'image' => '/images/avatars/3d/jeans.png'],
            ['name' => 'Traditional Abaya', 'category' => 'female', 'sub_category' => 'traditional', 'sort_order' => 5, 'image' => '/images/outfit-icons/traditional_female.png'],

            // Child outfits
            ['name' => 'Super Hero T-Shirt', 'category' => 'child', 'sub_category' => 'top', 'sort_order' => 1, 'image' => '/images/avatars/3d/shirt.png'],
            ['name' => 'Cotton Hoodie', 'category' => 'child', 'sub_category' => 'top', 'sort_order' => 2, 'image' => '/images/avatars/3d/shirt.png'],
            ['name' => 'Cargo Shorts', 'category' => 'child', 'sub_category' => 'bottom', 'sort_order' => 3, 'image' => '/images/avatars/3d/jeans.png'],
            ['name' => 'Track Pants', 'category' => 'child', 'sub_category' => 'bottom', 'sort_order' => 4, 'image' => '/images/avatars/3d/jeans.png'],
            ['name' => 'Kids Dishdasha', 'category' => 'child', 'sub_category' => 'traditional', 'sort_order' => 5, 'image' => '/images/outfit-icons/traditional_male.png'],
        ];

        // First, truncate the table to avoid duplicates
        Outfit::truncate();

        foreach ($outfits as $outfit) {
            Outfit::create($outfit);
        }
    }
}
