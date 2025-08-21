<?php

namespace Database\Seeders;

use App\Models\GiftItems;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GiftItems::create([
            'gift_items' => 'Achor Lunch Box',
            'qty' => 4800,
            'per_day' => 200,
        ]);
    }
}
