<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class PopulateProductSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:populate-product-slugs';

    /**
     * The console command description.
     *
     * @var stringw
     */
    protected $description = 'Populate the slug for all existing products.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $products = Product::all();

        foreach ($products as $product) {
            $product->slug = Str::slug($product->title);
            $product->save();
        }

        $this->info('Successfully populated product slugs.');
    }
}
