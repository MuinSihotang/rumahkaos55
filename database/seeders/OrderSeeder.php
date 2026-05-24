<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // --- 1. MEMBUAT DATA DUMMY PELANGGAN (CUSTOMERS) ---
        $customers = [];
        for ($i = 1; $i <= 5; $i++) {
            $customers[] = User::firstOrCreate(
                ['email' => "customer{$i}@gmail.com"],
                [
                    'name' => $faker->name,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
        }

        // Ambil semua varian produk yang ada di database
        $variants = ProductVariant::with('product')->get();

        if ($variants->isEmpty()) {
            $this->command->warn('Varian produk kosong! Jalankan TshirtSeeder terlebih dahulu.');
            return;
        }

        // --- 2. GENERATE 15 DATA PESANAN RANDOM ---
        for ($j = 1; $j <= 15; $j++) {
            $customer = $faker->randomElement($customers);
            $status = $faker->randomElement(['pending', 'processing', 'shipped', 'completed', 'cancelled']);
            
            // Format alamat pengiriman standar Indonesia
            $address = $faker->address . ', ' . $faker->city . ', Provinsi ' . $faker->state . ' - ' . $faker->postcode;
            
            // Random ongkir antara 10rb - 30rb
            $shippingCost = $faker->randomElement([10000, 15000, 22000, 30000]);

            // Buat data pesanan utama terlebih dahulu (grand_total diisi 0 sementara)
            $order = Order::create([
                'user_id' => $customer->id,
                'order_number' => 'INV-' . now()->format('Ymd') . '-' . str_pad($j, 3, '0', STR_PAD_LEFT),
                'status' => $status,
                'shipping_cost' => $shippingCost,
                'grand_total' => 0, 
                'shipping_address' => $address,
                'tracking_number' => $status === 'shipped' || $status === 'completed' ? 'REG' . $faker->numerify('##########') : null,
                'created_at' => $faker->dateTimeBetween('-1 month', 'now'), // Menyebar dalam 1 bulan terakhir
            ]);

            // Tentukan berapa banyak jenis t-shirt yang dibeli dalam satu invoice ini (1 sampai 3 jenis baju)
            $itemCount = $faker->numberBetween(1, 3);
            $randomVariants = $variants->random($itemCount);
            
            $subtotal = 0;

            foreach ($randomVariants as $variant) {
                $qty = $faker->numberBetween(1, 2); // Beli 1 atau 2 pcs per model baju
                $unitPrice = $variant->price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $variant->id,
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                ]);

                $subtotal += ($unitPrice * $qty);
            }

            // Update grand_total yang sebenarnya (Subtotal Produk + Ongkir)
            $order->update([
                'grand_total' => $subtotal + $shippingCost
            ]);
        }
    }
}