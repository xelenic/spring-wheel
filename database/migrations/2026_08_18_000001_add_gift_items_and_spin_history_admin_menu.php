<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Menu id 2 is the built-in "Admin" section created by AdminTablesSeeder.
        $adminSection = DB::table('admin_menu')->where('title', 'Admin')->where('parent_id', 0)->first();
        $parentId = $adminSection->id ?? 0;
        $maxOrder = (int) DB::table('admin_menu')->max('order');

        $links = [
            ['title' => 'Gift Items', 'icon' => 'icon-gift', 'uri' => 'gift-items'],
            ['title' => 'Spin History', 'icon' => 'icon-list', 'uri' => 'spin-history'],
        ];

        foreach ($links as $link) {
            $exists = DB::table('admin_menu')->where('uri', $link['uri'])->exists();

            if (!$exists) {
                $maxOrder++;

                DB::table('admin_menu')->insert([
                    'parent_id'  => $parentId,
                    'order'      => $maxOrder,
                    'title'      => $link['title'],
                    'icon'       => $link['icon'],
                    'uri'        => $link['uri'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('admin_menu')->whereIn('uri', ['gift-items', 'spin-history'])->delete();
    }
};
