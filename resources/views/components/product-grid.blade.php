@props(['products'])

<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-10">
            <h2 class="text-2xl lg:text-3xl font-bold tracking-tight text-black">NEW ARRIVALS</h2>
            <a href="#" class="text-sm font-medium text-black border-b border-black pb-0.5 hover:text-gray-600 hover:border-gray-600 transition-all">View All</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-12">
            @forelse($products as $product)
            <!-- Bungkus dengan tag <a> agar bisa di-klik menuju detail produk -->
            <a href="/product/{{ $product->slug }}" class="group cursor-pointer block">
                <div class="relative w-full aspect-[3/4] bg-white overflow-hidden mb-4">
                    
                    <!-- Menampilkan Gambar Produk -->
                    @if($product->image_path)
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500">
                    @else
                        <!-- Placeholder jika produk belum diberi gambar -->
                        <div class="absolute inset-0 bg-gray-200 flex items-center justify-center text-gray-400 group-hover:scale-105 transition-transform duration-500">
                            No Image
                        </div>
                    @endif

                    <!-- Label Badge -->
                    <div class="absolute top-4 left-4 bg-black text-white text-xs font-bold px-2 py-1 uppercase">New</div>
                </div>
                
                <!-- PERBAIKAN LAYOUT TEKS & HARGA -->
                <div class="flex justify-between items-start gap-4">
                    <!-- Bagian Kiri (Nama & Kategori) -->
                    <div class="flex-1 min-w-0">
                        <!-- line-clamp-2 membatasi teks max 2 baris dan menambah ... -->
                        <h3 class="text-sm font-medium text-black line-clamp-2 leading-snug">
                            {{ $product->name }}
                        </h3>
                        <!-- truncate membatasi teks kategori max 1 baris agar tetap rapi -->
                        <p class="mt-1 text-sm text-gray-500 truncate">
                            {{ $product->category->name ?? 'T-Shirt' }}
                        </p>
                    </div>
                    
                    <!-- Bagian Kanan (Harga) -->
                    <div class="shrink-0 text-right">
                        <!-- whitespace-nowrap memastikan angka harga tidak terpotong ke bawah -->
                        <p class="text-sm font-medium text-black whitespace-nowrap">
                            Rp {{ number_format($product->base_price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </a>
            @empty
            <!-- Tampilan jika belum ada produk sama sekali di database -->
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500">Belum ada produk yang ditambahkan.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>