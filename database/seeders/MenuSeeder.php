<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuGroup;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed menu groups
        $menuGroups = [
            ['name' => 'Header Navigation', 'slug' => 'header-navigation', 'description' => 'Header navigation menu', 'status' => true],
            ['name' => 'Footer Conditions', 'slug' => 'footer-conditions', 'description' => 'Footer conditions menu', 'status' => true],
            ['name' => 'Footer Treatments', 'slug' => 'footer-treatments', 'description' => 'Footer treatments menu', 'status' => true],
            ['name' => 'Footer Centres', 'slug' => 'footer-centres', 'description' => 'Footer centres menu', 'status' => true],
            ['name' => 'Footer Resources', 'slug' => 'footer-resources', 'description' => 'Footer resources menu', 'status' => true],
        ];

        foreach ($menuGroups as $groupData) {
            MenuGroup::firstOrCreate(
                ['slug' => $groupData['slug']],
                $groupData
            );
        }
    }
}
