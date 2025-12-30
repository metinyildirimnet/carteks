<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\OrderStatus::create(['name' => 'Draft', 'color' => '#6c757d']);
        \App\Models\OrderStatus::create(['name' => 'Pending', 'color' => '#ffc107']);
        \App\Models\OrderStatus::create(['name' => 'Processing', 'color' => '#17a2b8']);
        \App\Models\OrderStatus::create(['name' => 'Shipped', 'color' => '#007bff']);
        \App\Models\OrderStatus::create(['name' => 'Delivered', 'color' => '#28a745']);
        \App\Models\OrderStatus::create(['name' => 'Cancelled', 'color' => '#dc3545']);
    }
}
