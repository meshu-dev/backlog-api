<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    public function run()
    {
        $userIds = User::all()->pluck('id');
        $categoryIds = Category::all()->pluck('id');

        foreach (range(1, 50) as $index) {
            Item::factory()->create([
                'user_id' => $userIds->random(),
                'category_id' => $categoryIds->random()
            ]);
        } 

    }
}
