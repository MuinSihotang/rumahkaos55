<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class TshirtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Ambil semua ID Kategori yang sudah dibuat oleh CategorySeeder
        $categoryIds = Category::pluck('id')->toArray();

        // Pastikan ada kategori sebelum membuat produk
        if (empty($categoryIds)) {
            $this->command->warn('Kategori kosong! Pastikan CategorySeeder dijalankan lebih dulu.');
            return;
        }

        // --- DATA 20 T-SHIRT REALISTIS ---
        $tshirtNames = [
            'Kaos Polos Cotton Combed 30s Premium', 'Oversized T-Shirt Washed Vintage Black',
            'Graphic Tee Cyberpunk Neon City', 'Kaos Raglan Vintage 90s Edition',
            'Heavyweight T-Shirt Boxy Fit', 'Kaos Lengan Panjang Basic Ribbed',
            'Tie-Dye T-Shirt Summer Vibes Festival', 'Kaos Polos Cotton Bamboo Anti Bakteri',
            'Streetwear Tee Skull & Roses Print', 'Minimalist Essential Logo T-Shirt',
            'Kaos Henley Kancing Depan Casual', 'Oversized Tee Drop Shoulder Ultimate',
            'Retro Striped T-Shirt 80s Vibe', 'Kaos Polos V-Neck Premium Quality',
            'Graphic Tee Mountain Explorer Outdoor', 'Kaos Olahraga Dry-Fit Breathable',
            'Acid Wash T-Shirt Grunge Style', 'Anime Inspired Mecha Graphic Tee',
            'Kaos Polos Misty Grey 24s Tebal', 'Typography Quote Motivational T-Shirt'
        ];

        // --- PROSES PEMBUATAN PRODUK & VARIAN ---
        foreach ($tshirtNames as $index => $name) {
            $basePrice = $faker->randomElement([55000, 75000, 99000, 120000, 150000]);
            
            // Buat Produk dengan kategori acak dari database
            $product = Product::create([
                'category_id' => $faker->randomElement($categoryIds),
                'name' => $name,
                'slug' => Str::slug($name) . '-' . Str::random(4), 
                'description' => '<h3>Detail Produk</h3><p>' . $faker->paragraph(3) . '</p>',
                'base_price' => $basePrice,
                'is_active' => true,
                'image_path' => null,
                'gallery' => []
            ]);

            // Setup Varian (Warna & Ukuran)
            $sizes = ['S', 'M', 'L', 'XL', 'XXL'];
            $colors = ['Hitam', 'Putih', 'Navy', 'Maroon', 'Sage Green', 'Mustard', 'Abu Misty'];

            // Ambil 2 warna random dan 3 ukuran random per produk
            $selectedColors = $faker->randomElements($colors, 2);
            $selectedSizes = $faker->randomElements($sizes, 3);

            foreach ($selectedColors as $color) {
                foreach ($selectedSizes as $size) {
                    $variantPrice = $basePrice;
                    // Markup harga untuk ukuran besar
                    if ($size === 'XL') $variantPrice += 10000; 
                    if ($size === 'XXL') $variantPrice += 15000;

                    $sku = strtoupper('TSH-' . Str::slug($color) . '-' . $size . '-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT));

                    ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => $sku,
                        'color' => $color,
                        'size' => $size,
                        'price' => $variantPrice,
                        'stock' => $faker->numberBetween(5, 50),
                    ]);
                }
            }
        }
    }
}