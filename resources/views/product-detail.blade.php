<x-app-layout>
    <div class="bg-white selection:bg-black selection:text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-20">
            
            <!-- Breadcrumb Navigation -->
            <nav class="flex text-xs font-medium text-gray-400 mb-8 tracking-wider uppercase">
                <a href="/" class="hover:text-black transition-colors">Home</a>
                <span class="mx-3">/</span>
                <span class="hover:text-black transition-colors cursor-default">{{ $product->category->name ?? 'Kategori' }}</span>
                <span class="mx-3">/</span>
                <span class="text-black truncate max-w-[200px]">{{ $product->name }}</span>
            </nav>

            <!-- Grid Utama Detail Produk -->
            <div class="lg:grid lg:grid-cols-2 lg:gap-x-12 xl:gap-x-16">
                
                <!-- KIRI: Galeri Gambar Produk -->
                <div class="flex flex-col-reverse lg:flex-row gap-4 lg:gap-6">
                    <!-- Thumbnail Gallery -->
                    @if($product->gallery && is_array($product->gallery) && count($product->gallery) > 0)
                    <div class="flex lg:flex-col gap-4 overflow-x-auto lg:overflow-visible w-full lg:w-24 shrink-0 no-scrollbar">
                        <!-- Thumbnail Gambar Utama -->
                        <button class="w-20 h-24 lg:w-full lg:h-32 bg-gray-100 flex-shrink-0 border-2 border-black">
                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="Thumbnail Utama" class="w-full h-full object-cover">
                        </button>
                        <!-- Thumbnail Gambar Tambahan -->
                        @foreach($product->gallery as $image)
                        <button class="w-20 h-24 lg:w-full lg:h-32 bg-gray-100 flex-shrink-0 border border-transparent hover:border-gray-300 transition-colors">
                            <img src="{{ asset('storage/' . $image) }}" alt="Thumbnail Tambahan" class="w-full h-full object-cover opacity-70 hover:opacity-100">
                        </button>
                        @endforeach
                    </div>
                    @endif

                    <!-- Gambar Utama Membesar -->
                    <div class="w-full bg-gray-100 aspect-[3/4] relative">
                        @if($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center text-gray-400 font-medium">No Image Provided</div>
                        @endif
                    </div>
                </div>

                <!-- KANAN: Informasi & Aksi Produk -->
                <div class="mt-10 px-4 sm:px-0 lg:mt-0">
                    
                    <!-- 1. NAMA DAN HARGA -->
                    <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-black mb-2 uppercase">{{ $product->name }}</h1>
                    <p class="text-3xl font-bold text-black mb-8">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>

                    <form action="{{ route('cart.add') }}" method="POST" class="mb-10">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <!-- 2. PILIHAN VARIAN (UKURAN & WARNA) DI BAWAH HARGA -->
                        @if($product->variants && $product->variants->count() > 0)
                        <div class="mb-8 pt-4 border-t border-gray-200">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-sm font-bold text-black uppercase tracking-wider">Pilih Varian</h3>
                                <a href="#" class="text-xs font-medium text-gray-500 underline hover:text-black transition-colors">Size Guide</a>
                            </div>
                            
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @foreach($product->variants as $variant)
                                <!-- Label berfungsi sebagai tombol Radio kustom -->
                                <label class="relative border border-gray-300 rounded-none p-4 flex items-center justify-center cursor-pointer hover:border-black transition-all">
                                    <input type="radio" name="variant_id" value="{{ $variant->id }}" class="sr-only peer" required>
                                    <div class="text-sm font-medium text-black text-center peer-checked:font-bold">
                                        {{ $variant->size }} <br> 
                                        <span class="text-xs text-gray-500 font-normal peer-checked:text-black">{{ $variant->color }}</span>
                                    </div>
                                    <div class="absolute inset-0 border-2 border-transparent peer-checked:border-black pointer-events-none"></div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- TOMBOL ADD TO CART -->
                        <div class="flex gap-4">
                            <button type="submit" class="flex-1 bg-black text-white px-8 py-5 text-sm font-bold uppercase tracking-widest hover:bg-gray-800 transition-colors duration-300">
                                Add to Cart
                            </button>
                            <button type="button" class="px-5 py-5 border border-gray-300 text-black hover:border-black transition-colors flex items-center justify-center group">
                                <svg class="w-6 h-6 text-gray-400 group-hover:text-black transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </button>
                        </div>
                    </form>

                    <!-- 3. DESKRIPSI PRODUK DI BAWAH VARIAN & TOMBOL -->
                    <div class="pt-8 border-t border-gray-200">
                        <h3 class="text-sm font-bold text-black uppercase tracking-wider mb-4">Deskripsi Produk</h3>
                        <div class="prose prose-sm prose-gray max-w-none text-gray-600 leading-relaxed">
                            {!! $product->description ?? '<p>Tidak ada deskripsi untuk produk ini.</p>' !!}
                        </div>
                    </div>

                </div>
            </div>

            <!-- 4. KOMPONEN REKOMENDASI PRODUK LAINNYA -->
            <div class="mt-24 pt-16 border-t border-gray-200">
                <div class="flex justify-between items-end mb-10">
                    <h2 class="text-2xl lg:text-3xl font-bold tracking-tight text-black uppercase">Rekomendasi Lainnya</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-12">
                    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
                        @foreach($relatedProducts as $related)
                        <a href="/product/{{ $related->slug }}" class="group cursor-pointer block">
                            <div class="relative w-full aspect-[3/4] bg-white overflow-hidden mb-4">
                                @if($related->image_path)
                                    <img src="{{ asset('storage/' . $related->image_path) }}" alt="{{ $related->name }}" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="absolute inset-0 bg-gray-200 flex items-center justify-center text-gray-400 group-hover:scale-105 transition-transform duration-500">No Image</div>
                                @endif
                            </div>
                            
                            <div class="flex justify-between items-start gap-4">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-medium text-black line-clamp-2 leading-snug">{{ $related->name }}</h3>
                                    <p class="mt-1 text-sm text-gray-500 truncate">{{ $related->category->name ?? 'T-Shirt' }}</p>
                                </div>
                                <div class="shrink-0 text-right">
                                    <p class="text-sm font-medium text-black whitespace-nowrap">Rp {{ number_format($related->base_price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    @else
                        <!-- Tampilan default jika variabel $relatedProducts belum disiapkan di Route -->
                        <div class="col-span-full text-center py-12 bg-gray-50 border border-dashed border-gray-300">
                            <p class="text-gray-500 text-sm">Belum ada rekomendasi produk saat ini.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>