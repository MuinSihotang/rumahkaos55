<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    // Halaman New Arrivals: Menampilkan produk paling baru ditambahkan
    public function newArrivals()
    {
        $products = Product::with('category')
            ->where('is_active', true)
            ->latest() // Urutkan dari yang terbaru
            ->paginate(12); // Tampilkan 12 produk per halaman

        $pageTitle = 'New Arrivals';
        $pageDescription = 'Koleksi terbaru kami dengan potongan presisi dan material premium.';

        return view('shop', compact('products', 'pageTitle', 'pageDescription'));
    }

    // Halaman Best Sellers: (Sebagai contoh, kita tampilkan secara acak atau bisa diurutkan berdasarkan data lain nantinya)
    public function bestSellers()
    {
        $products = Product::with('category')
            ->where('is_active', true)
            ->inRandomOrder() // Acak produk untuk simulasi Best Seller
            ->paginate(12);

        $pageTitle = 'Best Sellers';
        $pageDescription = 'Produk favorit pelanggan kami. Temukan gaya yang paling banyak dicari.';

        return view('shop', compact('products', 'pageTitle', 'pageDescription'));
    }

    // Halaman Collections: Menampilkan semua produk
    public function collections(Request $request)
    {
        $query = Product::with('category')->where('is_active', true);

        // 1. Filter Pencarian (Nama Produk, Deskripsi, atau Nama Kategori)
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                // Cari dari nama produk
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  // ATAU cari dari deskripsi produk
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  // ATAU cari dari relasi tabel kategori (nama kategori)
                  ->orWhereHas('category', function($qCategory) use ($searchTerm) {
                      $qCategory->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // 2. Filter Berdasarkan Ukuran
        if ($request->filled('size')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('size', $request->size);
            });
        }

        // 3. Filter Berdasarkan Rentang Harga Minimum
        if ($request->filled('min_price')) {
            $query->where('base_price', '>=', $request->min_price);
        }

        // 4. Filter Berdasarkan Rentang Harga Maksimum
        if ($request->filled('max_price')) {
            $query->where('base_price', '<=', $request->max_price);
        }

        // 5. Eksekusi query dengan pagination
        $products = $query->paginate(12)->withQueryString();

        $pageTitle = $request->filled('search') ? 'Hasil Pencarian: "' . $request->search . '"' : 'All Collections';
        $pageDescription = $request->filled('search') ? 'Menampilkan produk yang sesuai dengan kata kunci pencarian Anda.' : 'Jelajahi seluruh koleksi pakaian dasar (basics) standar enterprise kami.';

        return view('shop', compact('products', 'pageTitle', 'pageDescription'));
    }
}