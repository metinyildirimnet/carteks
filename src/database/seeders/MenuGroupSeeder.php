<?php

namespace Database\Seeders;

use App\Models\MenuGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MenuGroup::firstOrCreate(
            ['key' => 'footer_1'],
            ['title' => 'Kurumsal']
        );
        MenuGroup::firstOrCreate(
            ['key' => 'footer_2'],
            ['title' => 'Müşteri Hizmetleri']
        );
        MenuGroup::firstOrCreate(
            ['key' => 'footer_3'],
            ['title' => 'Sözleşmeler']
        );
        MenuGroup::firstOrCreate(
            ['key' => 'footer_4'],
            ['title' => 'Faydalı Linkler']
        );
    }
}
