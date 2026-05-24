<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar kategori khusus T-Shirt yang lebih spesifik
        $categories = [
            'Kaos Lengan Pendek (Short Sleeve)',
            'Kaos Lengan Panjang (Long Sleeve)',
            'Kaos Oversize',
            'Kaos Polo (Polo Shirt)',
            'Graphic Tees (Sablon)',
            'Kaos Olahraga (Activewear)',
            'Kaos Basic (Polos)',
            'Kaos V-Neck'
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['slug' => Str::slug($cat)],
                ['name' => $cat]
            );
        }
    }
}