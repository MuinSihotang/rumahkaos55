<section class="relative bg-white pt-16 pb-24 lg:pt-32 lg:pb-40 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 flex flex-col lg:flex-row items-center justify-between">
        
        <!-- Sektor Tipografi Utama dan Call-to-Action (CTA) -->
        <div class="lg:w-1/2 text-center lg:text-left mb-16 lg:mb-0">
            <h1 class="text-5xl lg:text-7xl font-extrabold text-black tracking-tighter leading-tight mb-6">
                DAIRI<br/>PRIDE.
            </h1>
            <p class="text-lg lg:text-xl text-gray-500 max-w-lg mx-auto lg:mx-0 mb-10">
                T-shirt premium dengan potongan sempurna. Didesain untuk kenyamanan sehari-hari dengan material berkualitas tinggi standar enterprise.
            </p>
            <div class="flex flex-col sm:flex-row justify-center lg:justify-start space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('shop.collections') }}" class="inline-flex justify-center items-center px-8 py-4 bg-black text-white text-sm font-semibold tracking-widest uppercase hover:bg-gray-800 transition-colors duration-300">
                    BELANJA
                </a>
            </div>
        </div>

        <!-- Kontainer Media (Carousel Image) -->
        <div class="lg:w-1/2 flex justify-center lg:justify-end">
            <div class="w-full max-w-md relative group">
                
                <!-- Main Image Container -->
                <div class="aspect-[4/5] bg-gray-100 flex items-center justify-center overflow-hidden relative">
                    <img id="hero-carousel-image" src="{{ asset('images/hero-collection-1.jpeg') }}" alt="Gambar Utama" class="object-cover w-full h-full transition-opacity duration-300">
                    
                    <!-- Tombol Prev (Kiri) - Muncul saat dihover -->
                    <button type="button" onclick="prevHeroImage()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-black/60 hover:bg-black text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    
                    <!-- Tombol Next (Kanan) - Muncul saat dihover -->
                    <button type="button" onclick="nextHeroImage()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-black/60 hover:bg-black text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>

                <!-- Thumbnail Navigasi Bawah -->
                <div class="flex justify-center gap-3 mt-4">
                    <button type="button" onclick="setHeroImage(0)" class="w-16 h-20 bg-gray-100 border-2 border-black focus:outline-none transition-all hero-thumb">
                        <img src="{{ asset('images/hero-collection-1.jpeg') }}" class="w-full h-full object-cover">
                    </button>
                    <!-- Gambar Kedua (Sudah disesuaikan) -->
                    <button type="button" onclick="setHeroImage(1)" class="w-16 h-20 bg-gray-100 border border-transparent hover:border-gray-400 focus:outline-none transition-all hero-thumb">
                        <img src="{{ asset('images/Gomak 2.jpeg') }}" class="w-full h-full object-cover">
                    </button>
                    <!-- Gambar Ketiga (Sudah disesuaikan) -->
                    <button type="button" onclick="setHeroImage(2)" class="w-16 h-20 bg-gray-100 border border-transparent hover:border-gray-400 focus:outline-none transition-all hero-thumb">
                        <img src="{{ asset('images/Sidikalang Najolo 2.jpeg') }}" class="w-full h-full object-cover">
                    </button>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- Script Logika Carousel -->
<script>
    // 1. Definisikan array gambar kamu di sini (Sesuaikan dengan path di public/images)
    const heroImages = [
        "{{ asset('images/hero-collection-1.jpeg') }}",
        "{{ asset('images/Gomak 2.jpeg') }}",
        "{{ asset('images/Sidikalang Najolo 2.jpeg') }}",
    ];
    
    let currentHeroIndex = 0;
    const mainHeroImage = document.getElementById('hero-carousel-image');
    const thumbnails = document.querySelectorAll('.hero-thumb');

    // Fungsi untuk memperbarui gambar utama dan border pada thumbnail
    function updateHeroDisplay() {
        mainHeroImage.src = heroImages[currentHeroIndex];
        
        thumbnails.forEach((thumb, index) => {
            if (index === currentHeroIndex) {
                thumb.classList.add('border-2', 'border-black');
                thumb.classList.remove('border', 'border-transparent');
            } else {
                thumb.classList.remove('border-2', 'border-black');
                thumb.classList.add('border', 'border-transparent');
            }
        });
    }

    // Fungsi tombol Next
    function nextHeroImage() {
        currentHeroIndex = (currentHeroIndex + 1) % heroImages.length;
        updateHeroDisplay();
    }

    // Fungsi tombol Prev
    function prevHeroImage() {
        currentHeroIndex = (currentHeroIndex - 1 + heroImages.length) % heroImages.length;
        updateHeroDisplay();
    }

    // Fungsi klik dari Thumbnail
    function setHeroImage(index) {
        currentHeroIndex = index;
        updateHeroDisplay();
    }
</script>