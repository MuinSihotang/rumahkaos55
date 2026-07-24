<x-app-layout>
    
    <!-- Bagian Atas / Hero -->
    <x-hero />

    <!-- Bagian Fitur -->
    <x-features />

    <!-- Bagian Koleksi -->
    <x-collections />

    <!-- Bagian Etalase Produk -->
    <x-product-grid :products="$products" />
    
    <!-- Anda bisa menambahkan komponen lain seperti banner promo di sini nantinya -->
    
</x-app-layout>