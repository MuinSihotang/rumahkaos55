<nav class="sticky top-0 z-50 w-full bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            
            <!-- Elemen Identitas Merek (Logo) -->
            <div class="flex-shrink-0 flex items-center">
                <a href="/" class="text-2xl font-bold tracking-tighter uppercase text-black">
                    RUMAH KAOS 55
                </a>
            </div>
            
            <!-- Navigasi Utama (Tersembunyi pada Viewport Mobile) -->
            <div class="hidden md:flex space-x-8">
                <a href="{{ route('shop.new-arrivals') }}" class="text-black hover:text-gray-500 transition-colors duration-200 text-sm font-medium">New Arrivals</a>
                <a href="{{ route('shop.best-sellers') }}" class="text-black hover:text-gray-500 transition-colors duration-200 text-sm font-medium">Best Sellers</a>
                <a href="{{ route('shop.collections') }}" class="text-black hover:text-gray-500 transition-colors duration-200 text-sm font-medium">Collections</a>
            </div>

            <!-- Kontainer Aksi Pengguna (Pencarian, Keranjang, Autentikasi) -->
            <div class="flex items-center space-x-4 sm:space-x-6">
                
                <!-- Komponen Fungsionalitas Pencarian -->
                <!-- Mengirim parameter 'search' via metode GET ke route collections -->
                <form action="{{ route('shop.collections') }}" method="GET" class="relative flex items-center group">
                    <!-- Field Input Pencarian (Transisi pelebaran lebar berdasarkan status interaksi) -->
                    <input type="text" name="search" placeholder="Cari produk..." value="{{ request('search') }}"
                        class="{{ request('search') ? 'w-32 sm:w-48 opacity-100' : 'w-0 opacity-0 group-hover:w-32 sm:group-hover:w-48 group-hover:opacity-100 focus:w-32 sm:focus:w-48 focus:opacity-100' }} transition-all duration-300 ease-in-out bg-transparent border-b border-black border-t-0 border-l-0 border-r-0 focus:ring-0 px-2 py-1 text-sm text-black placeholder-gray-400 absolute right-8">
                    
                    <button type="submit" class="p-1 z-10 bg-white">
                        <svg class="w-5 h-5 text-black hover:text-gray-500 transition-colors cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </form>

                <!-- Komponen Keranjang Belanja dan Manajemen State Keranjang -->
                @php
                    // Ekstraksi data state keranjang dari sesi dan agregasi total kuantitas item
                    $cart = session()->get('cart', []);
                    $cartCount = array_sum(array_column($cart, 'quantity'));
                @endphp

                <a href="{{ route('checkout') }}" class="relative p-1 group">
                    <svg class="w-6 h-6 text-black group-hover:text-gray-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    
                    <!-- Indikator Notifikasi Kuantitas (Render bersyarat) -->
                    @if($cartCount > 0)
                    <span class="absolute -top-1 -right-1 bg-black text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full border-2 border-white group-hover:bg-gray-800 transition-colors">
                        {{ $cartCount }}
                    </span>
                    @endif
                </a>

                <!-- Manajemen Profil dan Status Autentikasi -->
                @guest
                    <!-- View Status Unauthenticated (Akses ke Route Login) -->
                    <a href="{{ route('login') }}" class="p-1 group text-black hover:text-gray-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </a>
                @endguest

                @auth
                    <!-- View Status Authenticated (Menu Dropdown Profil) -->
                    <div class="relative group cursor-pointer p-1">
                        <!-- Indikator User Aktif -->
                        <div class="flex items-center gap-2 text-black hover:text-gray-500 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span class="text-xs font-bold uppercase hidden sm:block">{{ explode(' ', Auth::user()->name)[0] }}</span>
                        </div>
                        
                        <!-- Konten Menu Dropdown -->
                        <div class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                            
                            <!-- Tautan Konfigurasi Profil Pengguna -->
                            <a href="{{ route('profile') }}" class="block w-full text-left px-4 py-3 text-sm text-black hover:bg-gray-50 transition-colors border-b border-gray-100">
                                Akun Saya
                            </a>

                            <!-- Aksi Terminasi Sesi (Logout) -->
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-3 text-sm text-black hover:bg-gray-50 hover:text-red-600 transition-colors">
                                    Sign out
                                </button>
                            </form>
                            
                        </div>
                    </div>
                @endauth

            </div>
        </div>
    </div>
</nav>