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
            ['name' => 'Formal Suit', 'category' => 'male', 'sort_order' => 1, 'image' => 'images/outfits/male-formal-suit.svg'],
            ['name' => 'Casual Shirt & Jeans', 'category' => 'male', 'sort_order' => 2, 'image' => 'images/outfits/male-casual-shirt-jeans.svg'],
            ['name' => 'T-Shirt & Shorts', 'category' => 'male', 'sort_order' => 3, 'image' => 'images/outfits/male-tshirt-shorts.svg'],
            ['name' => 'Traditional Thobe', 'category' => 'male', 'sort_order' => 4, 'image' => 'images/outfits/male-traditional-thobe.svg'],
            ['name' => 'Sports Wear', 'category' => 'male', 'sort_order' => 5, 'image' => 'images/outfits/male-sports-wear.svg'],
            ['name' => 'Business Casual', 'category' => 'male', 'sort_order' => 6, 'image' => 'images/outfits/male-business-casual.svg'],

            // Female outfits
            ['name' => 'Formal Dress', 'category' => 'female', 'sort_order' => 1, 'image' => 'images/outfits/female-formal-dress.svg'],
            ['name' => 'Casual Jeans & Top', 'category' => 'female', 'sort_order' => 2, 'image' => 'images/outfits/female-casual-jeans-top.svg'],
            ['name' => 'Frock/Dress', 'category' => 'female', 'sort_order' => 3, 'image' => 'images/outfits/female-frock-dress.svg'],
            ['name' => 'Traditional Abaya', 'category' => 'female', 'sort_order' => 4, 'image' => 'images/outfits/female-traditional-abaya.svg'],
            ['name' => 'Sports Wear', 'category' => 'female', 'sort_order' => 5, 'image' => 'images/outfits/female-sports-wear.svg'],
            ['name' => 'Business Suit', 'category' => 'female', 'sort_order' => 6, 'image' => 'images/outfits/female-business-suit.svg'],

            // Child outfits
            ['name' => 'School Uniform', 'category' => 'child', 'sort_order' => 1, 'image' => 'images/outfits/child-school-uniform.svg'],
            ['name' => 'Casual T-Shirt & Jeans', 'category' => 'child', 'sort_order' => 2, 'image' => 'images/outfits/child-casual-tshirt-jeans.svg'],
            ['name' => 'Party Dress/Suit', 'category' => 'child', 'sort_order' => 3, 'image' => 'images/outfits/child-party-dress-suit.svg'],
            ['name' => 'Traditional Outfit', 'category' => 'child', 'sort_order' => 4, 'image' => 'images/outfits/child-traditional-outfit.svg'],
            ['name' => 'Sports Wear', 'category' => 'child', 'sort_order' => 5, 'image' => 'images/outfits/child-sports-wear.svg'],
            ['name' => 'Casual Shorts & T-Shirt', 'category' => 'child', 'sort_order' => 6, 'image' => 'images/outfits/child-casual-shorts-tshirt.svg'],
        ];

        // First, truncate the table to avoid duplicates
        Outfit::truncate();

        foreach ($outfits as $outfit) {
            Outfit::create($outfit);
        }
    }
}
