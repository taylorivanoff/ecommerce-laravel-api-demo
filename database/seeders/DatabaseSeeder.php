<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Suppliers
        $fancyFursRatWear = User::create([
            'name' => 'Fancy Furs Rat Wear',
            'email' => 'fancy@ratwear.com',
            'password' => 'password',
            'role' => 'supplier',
        ]);

        $rodentRunway = User::create([
            'name' => 'Rodent Runway',
            'email' => 'runway@rodent.com',
            'password' => 'password',
            'role' => 'supplier',
        ]);

        // Products
        $halloweenCostume = new Product([
            'name'  => 'Pumpkin Pie Costume',
            'description' => 'Crafted in all orange for a mysterious and ominous finish, our pet rat costume is made with the softest acrylic yarns available ensuring a warm and comforting experience for your rat!',
            'price' => '20.00',
            'image' => 'images/pumpkin-pie.png',
        ]);
        $fancyFursRatWear->products()->save($halloweenCostume);

        $batRatCape = new Product([
            'name'  => 'Batrat Outfit',
            'description' => 'Your furry friend can join the holiday fun with this Batman themed Fleece Cape Costume.',
            'price' => '15.00',
            'image' => 'images/batrat.png',
        ]);
        $rodentRunway->products()->save($batRatCape);
    }
}
