<x-app-layout>
    <div class="bg-gray-50 min-h-screen py-10 selection:bg-black selection:text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumbs -->
            <div class="mb-6 text-sm text-gray-500">
                <a href="/" class="hover:text-black">Home</a> <span class="mx-2">/</span> <span class="text-black font-medium">Akun Saya</span>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-black text-white p-4 text-sm font-medium flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.style.display='none'" class="text-gray-300 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif

            <div class="lg:grid lg:grid-cols-12 lg:gap-8">
                
                <!-- SIDEBAR AKUN -->
                <div class="lg:col-span-3 mb-8 lg:mb-0">
                    <div class="bg-white border border-gray-200 p-6 mb-6">
                        <div class="flex items-center gap-4">
                            <div class="h-16 w-16 rounded-full bg-gray-100 border border-gray-300 flex items-center justify-center overflow-hidden">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider">Selamat Datang,</p>
                                <p class="text-base font-bold text-black line-clamp-1">{{ $user->name }}</p>
                            </div>
                        </div>
                    </div>

                    <nav class="bg-white border border-gray-200 flex flex-row lg:flex-col overflow-x-auto no-scrollbar">
                        <button id="btn-dashboard" onclick="switchTab('dashboard')" class="tab-btn w-full flex items-center gap-3 text-left px-6 py-4 text-sm font-bold uppercase tracking-wider border-l-4 border-black text-black bg-gray-50 transition-all whitespace-nowrap">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            Dashboard
                        </button>
                        <button id="btn-orders" onclick="switchTab('orders')" class="tab-btn w-full flex items-center gap-3 text-left px-6 py-4 text-sm font-bold uppercase tracking-wider border-l-4 border-transparent text-gray-500 hover:bg-gray-50 hover:text-black transition-all whitespace-nowrap">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            Pesanan Saya
                        </button>
                        <button id="btn-address" onclick="switchTab('address')" class="tab-btn w-full flex items-center gap-3 text-left px-6 py-4 text-sm font-bold uppercase tracking-wider border-l-4 border-transparent text-gray-500 hover:bg-gray-50 hover:text-black transition-all whitespace-nowrap">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Buku Alamat
                        </button>
                        <button id="btn-settings" onclick="switchTab('settings')" class="tab-btn w-full flex items-center gap-3 text-left px-6 py-4 text-sm font-bold uppercase tracking-wider border-l-4 border-transparent text-gray-500 hover:bg-gray-50 hover:text-black transition-all whitespace-nowrap border-t border-gray-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Pengaturan Akun
                        </button>
                    </nav>
                </div>

                <!-- AREA KONTEN UTAMA -->
                <div class="lg:col-span-9 space-y-6">
                    
                    <!-- 1. TAB DASHBOARD -->
                    <div id="tab-dashboard" class="tab-content block">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                            <div class="bg-white p-6 border border-gray-200">
                                <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Total Pesanan</p>
                                <p class="text-3xl font-black text-black">{{ $orders->count() }}</p>
                            </div>
                            <div class="bg-white p-6 border border-gray-200">
                                <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Menunggu Pembayaran</p>
                                <p class="text-3xl font-black text-black">{{ $orders->where('status', 'pending')->count() }}</p>
                            </div>
                            <div class="bg-white p-6 border border-gray-200">
                                <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Selesai</p>
                                <p class="text-3xl font-black text-black">{{ $orders->where('status', 'completed')->count() }}</p>
                            </div>
                        </div>

                        <div class="bg-white border border-gray-200 p-8">
                            <h2 class="text-lg font-bold text-black uppercase tracking-wider mb-6">Aktivitas Terakhir</h2>
                            @if($orders->count() > 0)
                                <p class="text-sm text-gray-600 mb-4">Pesanan terakhir Anda dibuat pada <span class="font-bold text-black">{{ $orders->first()->created_at->format('d F Y') }}</span>. Anda dapat melacak status pengiriman di menu Pesanan Saya.</p>
                                <button onclick="switchTab('orders')" class="text-sm font-bold border-b border-black text-black hover:text-gray-500 hover:border-gray-500 transition-colors">Lihat Semua Pesanan &rarr;</button>
                            @else
                                <div class="text-center py-10">
                                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                    <p class="text-sm text-gray-500">Belum ada aktivitas belanja.</p>
                                    <a href="/" class="mt-4 inline-block px-6 py-3 border border-transparent text-sm font-bold uppercase tracking-widest text-white bg-black hover:bg-gray-800">Mulai Belanja</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- 2. TAB PESANAN SAYA -->
                    <div id="tab-orders" class="tab-content hidden">
                        <div class="bg-white border border-gray-200 p-8">
                            <div class="flex justify-between items-end mb-6 border-b border-gray-200 pb-4">
                                <h2 class="text-lg font-bold text-black uppercase tracking-wider">Pesanan Saya</h2>
                                <span class="text-sm text-gray-500">{{ $orders->count() }} Pesanan</span>
                            </div>
                            
                            @if($orders->count() > 0)
                                <div class="space-y-8">
                                    @foreach($orders as $order)
                                        <div class="border border-gray-200 bg-white">
                                            
                                            <!-- Header Order -->
                                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                                <div class="flex flex-col sm:flex-row sm:gap-8 gap-2">
                                                    <div>
                                                        <p class="text-xs text-gray-500 uppercase tracking-wider">Tanggal Order</p>
                                                        <p class="text-sm font-bold text-black">{{ $order->created_at->format('d M Y') }}</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-gray-500 uppercase tracking-wider">Total</p>
                                                        <p class="text-sm font-bold text-black">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
                                                    </div>
                                                </div>
                                                <div class="text-left md:text-right">
                                                    <p class="text-xs text-gray-500 uppercase tracking-wider">Order ID</p>
                                                    <p class="text-sm font-bold text-black">#{{ $order->order_number }}</p>
                                                </div>
                                            </div>

                                            <!-- Visual Tracking Stepper -->
                                            <div class="px-6 py-6 border-b border-gray-100">
                                                @if($order->status == 'cancelled')
                                                    <!-- Jika Dibatalkan, Munculkan Banner Merah -->
                                                    <div class="p-4 bg-red-50 border border-red-200 text-center">
                                                        <p class="text-sm font-bold text-red-600 uppercase tracking-widest">Pesanan Dibatalkan</p>
                                                    </div>
                                                @else
                                                    <!-- Jika Normal, Tampilkan Stepper -->
                                                    <div class="relative max-w-2xl">
                                                        <div class="overflow-hidden h-1 mb-4 text-xs flex rounded bg-gray-200">
                                                            @php
                                                                $progress = match($order->status) {
                                                                    'pending' => '25%',
                                                                    'processing' => '50%',
                                                                    'shipped' => '75%',
                                                                    'completed' => '100%',
                                                                    default => '0%'
                                                                };
                                                            @endphp
                                                            <div style="width: {{ $progress }}" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-black transition-all duration-500"></div>
                                                        </div>
                                                        <div class="flex justify-between text-xs font-bold uppercase tracking-wider">
                                                            <span class="{{ in_array($order->status, ['pending', 'processing', 'shipped', 'completed']) ? 'text-black' : 'text-gray-400' }}">Pending</span>
                                                            <span class="{{ in_array($order->status, ['processing', 'shipped', 'completed']) ? 'text-black' : 'text-gray-400' }}">Diproses</span>
                                                            <span class="{{ in_array($order->status, ['shipped', 'completed']) ? 'text-black' : 'text-gray-400' }}">Dikirim</span>
                                                            <span class="{{ $order->status == 'completed' ? 'text-black' : 'text-gray-400' }}">Selesai</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Daftar Item -->
                                            <!-- (Bagian daftar item biarkan seperti aslinya, tidak ada yang diubah) -->
                                            <div class="px-6 py-4">
                                                <ul class="divide-y divide-gray-100">
                                                    @foreach($order->items as $item)
                                                        <li class="py-4 flex">
                                                            <div class="h-24 w-20 flex-shrink-0 bg-gray-100 border border-gray-200 overflow-hidden rounded-md">
                                                                @if($item->variant?->product?->image_path)
                                                                    <img src="{{ asset('storage/' . $item->variant->product->image_path) }}" alt="" class="w-full h-full object-cover">
                                                                @else
                                                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                                        <span class="text-xs">No Image</span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="ml-4 flex flex-1 flex-col justify-center">
                                                                <div class="flex justify-between text-base font-bold text-black">
                                                                    <!-- Tambahkan ?-> dan fallback teks -->
                                                                    <h3 class="uppercase">{{ $item->variant?->product?->name ?? 'Produk Tidak Tersedia' }}</h3>
                                                                    <p class="ml-4 whitespace-nowrap">Rp {{ number_format($item->unit_price ?? 0, 0, ',', '.') }}</p>
                                                                </div>
                                                                <p class="mt-1 text-sm text-gray-500">
                                                                    <!-- Tambahkan ?-> dan fallback strip -->
                                                                    Varian: {{ $item->variant?->size ?? '-' }} / {{ $item->variant?->color ?? '-' }} 
                                                                </p>
                                                                <p class="mt-1 text-sm font-medium text-black">Qty: {{ $item->quantity }}</p>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>

                                            <!-- Aksi & Resi -->
                                            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                                                <div>
                                                    @if($order->tracking_number)
                                                        <p class="text-xs text-gray-500 uppercase tracking-wider">No Resi Pengiriman</p>
                                                        <p class="text-sm font-black text-black tracking-widest">{{ $order->tracking_number }}</p>
                                                    @else
                                                        <p class="text-xs text-gray-500 italic">Resi belum tersedia</p>
                                                    @endif
                                                </div>
                                                <div class="flex gap-3 w-full sm:w-auto">
                                                    
                                                    <!-- LOGIKA TOMBOL INVOICE -->
                                                    @if($order->status == 'cancelled')
                                                        <button disabled class="w-full sm:w-auto text-center px-4 py-2 border border-gray-200 text-xs font-bold uppercase tracking-widest text-gray-400 bg-gray-100 cursor-not-allowed">Invoice</button>
                                                    @else
                                                        <a href="{{ route('order.invoice', $order->id) }}" target="_blank" class="w-full sm:w-auto text-center px-4 py-2 border border-gray-300 text-xs font-bold uppercase tracking-widest text-black bg-white hover:bg-gray-50">Invoice</a>
                                                    @endif

                                                    <!-- LOGIKA TOMBOL BAYAR/BELI LAGI -->
                                                    @if($order->status == 'pending')
                                                        <a href="{{ route('order.pay', $order->id) }}" class="w-full sm:w-auto text-center px-4 py-2 border border-transparent text-xs font-bold uppercase tracking-widest text-white bg-black hover:bg-gray-800">Bayar Sekarang</a>
                                                    @elseif($order->status == 'completed')
                                                        <a href="/" class="w-full sm:w-auto text-center px-4 py-2 border border-transparent text-xs font-bold uppercase tracking-widest text-white bg-black hover:bg-gray-800">Beli Lagi</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12 bg-gray-50 border border-dashed border-gray-300">
                                    <p class="text-sm text-gray-500">Anda belum pernah melakukan pemesanan.</p>
                                    <a href="/" class="mt-4 inline-block font-bold text-black border-b border-black hover:text-gray-600 hover:border-gray-600 transition-colors">Mulai Belanja</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- 3. TAB BUKU ALAMAT -->
                    <div id="tab-address" class="tab-content hidden">
                        <div class="bg-white border border-gray-200 p-8">
                            <div class="flex justify-between items-center mb-6 border-b border-gray-200 pb-4">
                                <h2 class="text-lg font-bold text-black uppercase tracking-wider">Buku Alamat</h2>
                                <button onclick="openModal('addAddressModal')" class="text-sm font-bold text-black border-b border-black hover:text-gray-600 hover:border-gray-600 transition-colors">+ Tambah Alamat</button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                @forelse($addresses as $address)
                                    <div class="border-2 {{ $address->is_primary ? 'border-black' : 'border-gray-200' }} p-6 relative">
                                        @if($address->is_primary)
                                            <span class="absolute top-0 right-0 bg-black text-white text-[10px] font-bold uppercase tracking-wider px-2 py-1 m-4">Alamat Utama</span>
                                        @endif
                                        
                                        <h3 class="font-bold text-black mb-1 uppercase">{{ $address->receiver_name }}</h3>
                                        <p class="text-sm text-gray-500 mb-4">{{ $address->phone_number }}</p>
                                        <p class="text-sm text-gray-700 leading-relaxed mb-6">
                                            {{ $address->full_address }}<br>
                                            Kec. {{ $address->district }}, {{ $address->city }}<br>
                                            {{ $address->province }}, {{ $address->postal_code }}
                                        </p>
                                        
                                        <div class="flex gap-4">
                                            <button type="button" onclick="openEditModal({{ $address }})" class="text-xs font-bold uppercase tracking-wider text-black border-b border-black hover:text-gray-500">Edit</button>
                                            
                                            <!-- Form Hapus -->
                                            <form action="{{ route('profile.address.destroy', $address->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alamat ini?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs font-bold uppercase tracking-wider text-red-600 border-b border-red-600 hover:text-red-400">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-full text-center py-10 bg-gray-50 border border-dashed border-gray-300">
                                        <p class="text-sm text-gray-500 mb-2">Anda belum memiliki alamat tersimpan.</p>
                                    </div>
                                @endforelse

                                <!-- Tombol Tambah Alamat Card -->
                                <div onclick="openModal('addAddressModal')" class="border border-dashed border-gray-300 p-6 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-gray-50 transition-colors min-h-[200px]">
                                    <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"></path></svg>
                                    <p class="text-sm font-bold text-gray-600 uppercase">Tambah Alamat Baru</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. TAB PENGATURAN -->
                    <div id="tab-settings" class="tab-content hidden">
                        <div class="bg-white border border-gray-200 p-8">
                            <h2 class="text-lg font-bold text-black uppercase tracking-wider mb-6 border-b border-gray-200 pb-4">Pengaturan Akun</h2>
                            
                            @if ($errors->any())
                                <div class="mb-6 bg-red-50 text-red-700 p-4 text-sm border border-red-200">
                                    <ul class="list-disc pl-5">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('profile.update') }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    
                                    <!-- Kolom Kiri: Profil Dasar -->
                                    <div class="space-y-5">
                                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Informasi Dasar</h3>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:ring-0 focus:border-black sm:text-sm bg-white transition-colors">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email (Tidak dapat diubah)</label>
                                            <input type="email" value="{{ $user->email }}" disabled class="w-full px-4 py-3 border border-gray-200 bg-gray-50 text-gray-500 sm:text-sm cursor-not-allowed">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nomor Telepon (Opsional)</label>
                                            <input type="text" name="phone" placeholder="Contoh: 08123456789" class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:ring-0 focus:border-black sm:text-sm bg-white transition-colors">
                                        </div>
                                    </div>

                                    <!-- Kolom Kanan: Keamanan -->
                                    <div class="space-y-5">
                                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Keamanan (Ubah Password)</h3>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Password Baru</label>
                                            <input type="password" name="password" placeholder="Kosongkan jika tidak ingin diubah" class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:ring-0 focus:border-black sm:text-sm bg-white transition-colors">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Konfirmasi Password</label>
                                            <input type="password" name="password_confirmation" placeholder="Ulangi password baru" class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:ring-0 focus:border-black sm:text-sm bg-white transition-colors">
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-10 pt-6 border-t border-gray-200 flex justify-end">
                                    <button type="submit" class="w-full md:w-auto flex justify-center py-4 px-8 border border-transparent text-sm font-bold uppercase tracking-widest text-white bg-black hover:bg-gray-800 transition-colors">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- ==========================================
         MODAL TAMBAH & EDIT ALAMAT 
    =========================================== -->

    <!-- Modal Tambah -->
    <div id="addAddressModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center px-4">
        <div class="bg-white w-full max-w-2xl border border-gray-200 shadow-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                <h3 class="text-lg font-bold text-black uppercase tracking-wider">Tambah Alamat Baru</h3>
                <button type="button" onclick="closeModal('addAddressModal')" class="text-gray-400 hover:text-black">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('profile.address.store') }}" method="POST" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Penerima</label>
                        <input type="text" name="receiver_name" required class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-black sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">No. Handphone</label>
                        <input type="text" name="phone_number" required class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-black sm:text-sm">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Alamat Lengkap (Jalan, No Rumah, RT/RW)</label>
                    <textarea name="full_address" rows="3" required class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-black sm:text-sm"></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Provinsi</label>
                        <input type="text" name="province" required class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-black sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kota / Kabupaten</label>
                        <input type="text" name="city" required class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-black sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kecamatan</label>
                        <input type="text" name="district" required class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-black sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kode Pos</label>
                        <input type="text" name="postal_code" required class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-black sm:text-sm">
                    </div>
                </div>
                <div class="mb-6 flex items-center">
                    <input type="checkbox" name="is_primary" id="is_primary_add" value="1" class="h-4 w-4 text-black border-gray-300 focus:ring-black">
                    <label for="is_primary_add" class="ml-2 text-sm text-gray-600">Jadikan sebagai alamat utama</label>
                </div>
                <button type="submit" class="w-full py-4 px-8 border border-transparent text-sm font-bold uppercase tracking-widest text-white bg-black hover:bg-gray-800">Simpan Alamat</button>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="editAddressModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center px-4">
        <div class="bg-white w-full max-w-2xl border border-gray-200 shadow-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                <h3 class="text-lg font-bold text-black uppercase tracking-wider">Edit Alamat</h3>
                <button type="button" onclick="closeModal('editAddressModal')" class="text-gray-400 hover:text-black">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form id="editAddressForm" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Penerima</label>
                        <input type="text" name="receiver_name" id="edit_receiver_name" required class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-black sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">No. Handphone</label>
                        <input type="text" name="phone_number" id="edit_phone_number" required class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-black sm:text-sm">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Alamat Lengkap</label>
                    <textarea name="full_address" id="edit_full_address" rows="3" required class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-black sm:text-sm"></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Provinsi</label>
                        <input type="text" name="province" id="edit_province" required class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-black sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kota / Kabupaten</label>
                        <input type="text" name="city" id="edit_city" required class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-black sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kecamatan</label>
                        <input type="text" name="district" id="edit_district" required class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-black sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kode Pos</label>
                        <input type="text" name="postal_code" id="edit_postal_code" required class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-black sm:text-sm">
                    </div>
                </div>
                <div class="mb-6 flex items-center">
                    <input type="checkbox" name="is_primary" id="edit_is_primary" value="1" class="h-4 w-4 text-black border-gray-300 focus:ring-black">
                    <label for="edit_is_primary" class="ml-2 text-sm text-gray-600">Jadikan sebagai alamat utama</label>
                </div>
                <button type="submit" class="w-full py-4 px-8 border border-transparent text-sm font-bold uppercase tracking-widest text-white bg-black hover:bg-gray-800">Simpan Perubahan</button>
            </form>
        </div>
    </div>


    <!-- ==========================================
         JAVASCRIPT
    =========================================== -->
    <script>
        // Script untuk pindah-pindah Tab
        function switchTab(tabId) {
            // Sembunyikan semua konten
            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.add('hidden');
                el.classList.remove('block');
            });
            
            // Reset state tombol (hapus style aktif)
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('border-black', 'text-black', 'bg-gray-50');
                el.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Tampilkan tab terpilih
            document.getElementById('tab-' + tabId).classList.remove('hidden');
            document.getElementById('tab-' + tabId).classList.add('block');
            
            // Aktifkan style tombol terpilih
            const activeBtn = document.getElementById('btn-' + tabId);
            activeBtn.classList.remove('border-transparent', 'text-gray-500');
            activeBtn.classList.add('border-black', 'text-black', 'bg-gray-50');
        }

        // Script untuk buka tutup Modal
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Ambil parameter dari URL
            const urlParams = new URLSearchParams(window.location.search);
            const targetTab = urlParams.get('tab');
            
            // Jika ada parameter tab (misalnya: ?tab=orders), maka jalankan switchTab
            if (targetTab) {
                switchTab(targetTab);
            }
        });

        // Script untuk mengirim data alamat ke form edit secara dinamis
        function openEditModal(address) {
            // Mengatur action form (URL tujuan update ke ID alamat yang dipilih)
            document.getElementById('editAddressForm').action = `/profile/address/${address.id}`;
            
            // Mengisi value input form secara dinamis
            document.getElementById('edit_receiver_name').value = address.receiver_name;
            document.getElementById('edit_phone_number').value = address.phone_number;
            document.getElementById('edit_full_address').value = address.full_address;
            document.getElementById('edit_province').value = address.province;
            document.getElementById('edit_city').value = address.city;
            document.getElementById('edit_district').value = address.district;
            document.getElementById('edit_postal_code').value = address.postal_code;
            
            // Mengatur status checkbox (1 berarti true di database)
            document.getElementById('edit_is_primary').checked = address.is_primary == 1;

            openModal('editAddressModal');
        }
    </script>
</x-app-layout>