<x-app-layout>
    <div class="bg-white min-h-screen pt-12 pb-24 selection:bg-black selection:text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Komponen Header Halaman -->
            <div class="text-center mb-16 pt-8 border-b border-gray-200 pb-12">
                <h1 class="text-4xl md:text-5xl font-extrabold text-black uppercase tracking-tighter mb-4">
                    {{ $pageTitle }}
                </h1>
                <p class="text-gray-500 max-w-2xl mx-auto text-sm md:text-base">
                    {{ $pageDescription }}
                </p>
            </div>

            <!-- Grid Layout Utama Pembagian Area Filter dan Katalog Produk -->
            <div class="lg:grid lg:grid-cols-4 lg:gap-x-12">
                
                <!-- Panel Sidebar Filter Pencarian -->
                <div class="hidden lg:block lg:col-span-1">
                    <form action="{{ url()->current() }}" method="GET" class="sticky top-24">
                        <h2 class="text-lg font-bold text-black uppercase tracking-wider mb-6">Filter Produk</h2>
                        
                        <!-- Opsi Filter Berdasarkan Ukuran Produk -->
                        <div class="mb-8 pb-8 border-b border-gray-200">
                            <h3 class="text-sm font-bold text-black uppercase tracking-wider mb-4">Ukuran</h3>
                            <div class="space-y-3">
                                @foreach(['S', 'M', 'L', 'XL', 'XXL'] as $size)
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="size" value="{{ $size }}" 
                                        {{ request('size') == $size ? 'checked' : '' }}
                                        class="h-4 w-4 border-gray-300 text-black focus:ring-black">
                                    <span class="ml-3 text-sm text-gray-600 uppercase">{{ $size }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Opsi Filter Berdasarkan Rentang Harga -->
                        <div class="mb-8 pb-8 border-b border-gray-200">
                            <h3 class="text-sm font-bold text-black uppercase tracking-wider mb-4">Rentang Harga</h3>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Min (Rp)</label>
                                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="0" class="w-full border border-gray-300 p-2 focus:border-black focus:ring-0 text-xs">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Max (Rp)</label>
                                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="0" class="w-full border border-gray-300 p-2 focus:border-black focus:ring-0 text-xs">
                                </div>
                            </div>
                        </div>

                        <!-- Grup Tombol Eksekusi dan Reset Filter -->
                        <div class="flex gap-2">
                            <button type="submit" class="w-full bg-black text-white text-xs font-bold uppercase tracking-widest py-3 hover:bg-gray-800 transition-colors">
                                Apply Filter
                            </button>
                            @if(request()->anyFilled(['size', 'min_price', 'max_price']))
                            <a href="{{ url()->current() }}" class="w-full bg-white border border-gray-300 text-black text-center text-xs font-bold uppercase tracking-widest py-3 hover:bg-gray-50 transition-colors">
                                Reset
                            </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Wadah Utama Daftar Produk -->
                <div class="lg:col-span-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12 mb-16">
                        @forelse($products as $product)
                        <a href="/product/{{ $product->slug }}" class="group cursor-pointer block">
                            <div class="relative w-full aspect-[3/4] bg-white overflow-hidden mb-4">
                                @if($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="absolute inset-0 bg-gray-200 flex items-center justify-center text-gray-400 group-hover:scale-105 transition-transform duration-500">No Image</div>
                                @endif
                            </div>
                            
                            <div class="flex justify-between items-start gap-4">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-medium text-black line-clamp-2 leading-snug">{{ $product->name }}</h3>
                                    <p class="mt-1 text-sm text-gray-500 truncate">{{ $product->category->name ?? 'T-Shirt' }}</p>
                                </div>
                                <div class="shrink-0 text-right">
                                    <p class="text-sm font-medium text-black whitespace-nowrap">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </a>
                        @empty
                        <div class="col-span-full text-center py-20 bg-gray-50 border border-dashed border-gray-300">
                            <p class="text-gray-500">Tidak ada produk yang sesuai dengan filter Anda.</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Komponen Navigasi Paginasi -->
                    <div class="mt-12">
                        {{ $products->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>