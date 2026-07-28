<x-app-layout>
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-extrabold text-black uppercase tracking-tight mb-10">Checkout</h1>

            <!-- Komponen Notifikasi Status Manipulasi Keranjang (Flash Session) -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 text-green-700 p-4 text-sm border border-green-200">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-red-50 text-red-700 p-4 text-sm border border-red-200">
                    {{ session('error') }}
                </div>
            @endif

            <div class="lg:grid lg:grid-cols-12 lg:gap-x-12">
                <!-- Kolom Primer: Formulir Data Pengiriman Pengguna -->
                <div class="lg:col-span-7">
                    <div class="bg-white p-8 border border-gray-200">
                        <h2 class="text-lg font-bold text-black uppercase tracking-wider mb-6 pb-4 border-b border-gray-200">Detail Pengiriman</h2>
                        
                        <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                            @csrf
                            <div class="grid grid-cols-1 gap-y-6">
                                
                                @if($addresses->count() == 0)
                                    <!-- MUNCUL HANYA JIKA USER BELUM PUNYA ALAMAT -->
                                    <div>
                                        <label class="block text-sm font-medium text-black mb-2">Nama Lengkap</label>
                                        <input type="text" name="name" value="{{ auth()->user()->name }}" required class="w-full border-gray-300 focus:border-black focus:ring-0 rounded-none shadow-sm sm:text-sm p-3 border">
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-black mb-2">Email</label>
                                            <input type="email" name="email" value="{{ auth()->user()->email }}" required class="w-full border-gray-300 focus:border-black focus:ring-0 rounded-none shadow-sm sm:text-sm p-3 border">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-black mb-2">Nomor WhatsApp</label>
                                            <input type="text" name="phone" required class="w-full border-gray-300 focus:border-black focus:ring-0 rounded-none shadow-sm sm:text-sm p-3 border">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-black mb-2">Alamat Lengkap (Beserta Kota, Kecamatan, Kodepos)</label>
                                        <textarea name="address" rows="4" required class="w-full border-gray-300 focus:border-black focus:ring-0 rounded-none shadow-sm sm:text-sm p-3 border"></textarea>
                                    </div>
                                    
                                @else
                                    <!-- MUNCUL JIKA USER SUDAH PUNYA ALAMAT (FORM MANUAL DISEMBUNYIKAN) -->
                                    <div>
                                        <div class="flex justify-between items-center mb-4">
                                            <label class="block text-sm font-medium text-black">Pilih Alamat Pengiriman</label>
                                            <a href="{{ route('profile') }}" class="text-xs font-bold border-b border-black text-black hover:text-gray-500 hover:border-gray-500 transition-colors">Kelola Alamat</a>
                                        </div>
                                        
                                        <div class="space-y-4">
                                            @foreach($addresses as $address)
                                                @php
                                                    $formattedAddress = $address->receiver_name . ' (' . $address->phone_number . ') - ' . $address->full_address . ', Kec. ' . $address->district . ', ' . $address->city . ', ' . $address->province . ', ' . $address->postal_code;
                                                @endphp
                                                
                                                <label class="flex items-start p-5 border cursor-pointer transition-colors {{ ($addresses->count() == 1 || $address->is_primary) ? 'border-black bg-gray-50' : 'border-gray-200 bg-white hover:bg-gray-50' }}">
                                                    <div class="flex-shrink-0 mt-0.5">
                                                        <input type="radio" name="address" value="{{ $formattedAddress }}" required 
                                                            class="h-4 w-4 text-black border-gray-300 focus:ring-black"
                                                            {{ ($addresses->count() == 1 || $address->is_primary) ? 'checked' : '' }}>
                                                    </div>
                                                    <div class="ml-4 flex-1">
                                                        <p class="text-sm font-bold text-black uppercase">
                                                            {{ $address->receiver_name }} 
                                                            @if($address->is_primary) 
                                                                <span class="ml-2 text-[10px] bg-black text-white px-2 py-0.5 uppercase tracking-widest">Utama</span> 
                                                            @endif
                                                        </p>
                                                        <p class="text-xs text-gray-500 mt-1 font-medium">{{ $address->phone_number }}</p>
                                                        <p class="text-xs text-gray-700 mt-2 leading-relaxed">
                                                            {{ $address->full_address }}<br>
                                                            Kec. {{ $address->district }}, {{ $address->city }}<br>
                                                            {{ $address->province }}, {{ $address->postal_code }}
                                                        </p>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Kolom Sekunder: Ringkasan Item Pesanan (Sticky Order Summary) -->
                <div class="lg:col-span-5 mt-10 lg:mt-0">
                    <div class="bg-white p-8 border border-gray-200 sticky top-24">
                        <h2 class="text-lg font-bold text-black uppercase tracking-wider mb-6 pb-4 border-b border-gray-200">Ringkasan Pesanan</h2>
                        
                        <div class="flow-root mb-6">
                            <ul class="-my-4 divide-y divide-gray-200">
                                <!-- Iterasi Item Keranjang Beserta Indeks Kunci (Key) -->
                                @foreach($cart as $key => $item)
                                <li class="flex py-6">
                                    <div class="h-24 w-20 flex-shrink-0 bg-gray-100 border border-gray-200">
                                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="h-full w-full object-cover">
                                    </div>
                                    <div class="ml-4 flex flex-1 flex-col justify-between">
                                        <div>
                                            <div class="flex justify-between text-sm font-bold text-black">
                                                <h3 class="line-clamp-2 uppercase leading-tight">{{ $item['name'] }}</h3>
                                                <p class="ml-4 whitespace-nowrap">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500 uppercase tracking-widest">{{ $item['size'] }} | {{ $item['color'] }}</p>
                                        </div>
                                        
                                        <!-- Antarmuka Mutasi Keranjang: Tambah, Kurangi, dan Hapus -->
                                        <div class="flex items-center justify-between mt-2">
                                            
                                            <!-- Grup Kontrol Penyesuaian Kuantitas Produk -->
                                            <div class="flex items-center border border-gray-300">
                                                <form action="{{ route('cart.update', $key) }}" method="POST" class="m-0">
                                                    @csrf
                                                    <input type="hidden" name="action" value="decrease">
                                                    <button type="submit" class="px-3 py-1 text-gray-500 hover:text-black hover:bg-gray-100 transition-colors font-bold" {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>−</button>
                                                </form>
                                                
                                                <span class="px-3 py-1 text-sm font-medium text-black border-x border-gray-300 min-w-[40px] text-center">{{ $item['quantity'] }}</span>
                                                
                                                <form action="{{ route('cart.update', $key) }}" method="POST" class="m-0">
                                                    @csrf
                                                    <input type="hidden" name="action" value="increase">
                                                    <button type="submit" class="px-3 py-1 text-gray-500 hover:text-black hover:bg-gray-100 transition-colors font-bold">+</button>
                                                </form>
                                            </div>
                                            
                                            <!-- Eksekusi Penghapusan Item dari State Keranjang -->
                                            <form action="{{ route('cart.remove', $key) }}" method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="text-xs font-bold text-gray-400 hover:text-red-600 uppercase tracking-wider flex items-center gap-1 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    Hapus
                                                </button>
                                            </form>

                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="border-t border-gray-200 pt-6 mb-6">
                            <div class="flex justify-between text-base font-black text-black uppercase">
                                <p>Total Pembayaran</p>
                                <p>Rp {{ number_format($total, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <button type="submit" form="checkout-form" class="w-full bg-black text-white px-6 py-4 text-sm font-bold uppercase tracking-widest hover:bg-gray-800 transition-colors">
                            Lanjutkan Pembayaran
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>