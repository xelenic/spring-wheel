<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\GiftItems;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        GiftItems::create([
            'gift_items' => 'Anchor Lunch Box',
            'qty' => 4800,
            'per_day' => 200,
        ]);

        GiftItems::create([
            'gift_items' => 'Anchor Water Bottles',
            'qty' => 600,
            'per_day' => 25,
        ]);

        GiftItems::create([
            'gift_items' => 'Anchor Container',
            'qty' => 4200,
            'per_day' => 175,
        ]);

        GiftItems::create([
            'gift_items' => 'Pens',
            'qty' => 2400,
            'per_day' => 100,
        ]);
    }
}
