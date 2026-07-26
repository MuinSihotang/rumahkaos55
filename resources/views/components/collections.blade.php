<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- Komponen Banner Kedatangan Produk Baru -->
            <div class="relative group aspect-[4/3] bg-gray-100 overflow-hidden cursor-pointer">
                <div class="absolute inset-0 bg-gray-200 flex items-center justify-center text-gray-400 group-hover:scale-105 transition-transform duration-700 ease-in-out">
                    <img src="{{ asset('images/Sidikalang Najolo 2.jpeg') }}" alt="Gambar Model Pria" class="object-cover w-full h-full">
                </div>
                <div class="absolute inset-0 bg-black bg-opacity-20 group-hover:bg-opacity-30 transition-opacity duration-500"></div>
                <div class="absolute bottom-8 left-8 md:bottom-12 md:left-12">
                    <h3 class="text-2xl md:text-3xl font-bold text-white tracking-tight mb-2">NEW ARRIVALS</h3>
                    <a href="{{ route('shop.new-arrivals') }}" class="inline-flex items-center text-sm font-medium text-white border-b border-white pb-1 hover:text-gray-200 hover:border-gray-200 transition-colors">
                        View All &rarr;
                    </a>
                </div>
            </div>

            <!-- Komponen Banner Daftar Produk Terlaris -->
            <div class="relative group aspect-[4/3] bg-gray-100 overflow-hidden cursor-pointer">
                <div class="absolute inset-0 bg-gray-200 flex items-center justify-center text-gray-400 group-hover:scale-105 transition-transform duration-700 ease-in-out">
                    <img src="{{ asset('images/Gomak 2.jpeg') }}" alt="Gambar Model Wanita" class="object-cover w-full h-full">
                </div>
                <div class="absolute inset-0 bg-black bg-opacity-20 group-hover:bg-opacity-30 transition-opacity duration-500"></div>
                <div class="absolute bottom-8 left-8 md:bottom-12 md:left-12">
                    <h3 class="text-2xl md:text-3xl font-bold text-white tracking-tight mb-2">BEST SELLERS</h3>
                    <a href="{{ route('shop.best-sellers') }}" class="inline-flex items-center text-sm font-medium text-white border-b border-white pb-1 hover:text-gray-200 hover:border-gray-200 transition-colors">
                        View All &rarr;
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>