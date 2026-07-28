<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $order->order_number }}</title>
    <!-- Gunakan Tailwind CSS via CDN khusus untuk halaman invoice agar rapi -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Sembunyikan tombol cetak saat diprint (di kertas sungguhan) */
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-gray-100 p-8 text-black font-sans">

    <div class="max-w-3xl mx-auto bg-white p-10 border border-gray-200 shadow-sm">
        
        <!-- Header Invoice -->
        <div class="flex justify-between items-start mb-12 border-b border-black pb-6">
            <div>
                <h1 class="text-4xl font-black tracking-tighter uppercase">RUMAH KAOS 55.</h1>
                <p class="text-sm text-gray-500 mt-1">DAIRI PRIDE - Original Enterprise</p>
            </div>
            <div class="text-right">
                <h2 class="text-2xl font-bold uppercase tracking-widest text-gray-300">INVOICE</h2>
                <p class="text-sm font-bold mt-2">#{{ $order->order_number }}</p>
                <p class="text-sm text-gray-500">Tanggal: {{ $order->created_at->format('d M Y') }}</p>
            </div>
        </div>

        <!-- Info Pelanggan & Pengiriman -->
        <div class="flex justify-between mb-10">
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Ditagih Kepada:</h3>
                <p class="text-sm font-bold uppercase">{{ $order->user->name }}</p>
            </div>
            <div class="text-right w-1/2">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Dikirim Ke:</h3>
                <p class="text-sm leading-relaxed">{{ $order->shipping_address }}</p>
            </div>
        </div>

        <!-- Tabel Item -->
        <table class="w-full text-left mb-10 border-collapse">
            <thead>
                <tr class="border-b-2 border-black">
                    <th class="py-3 text-xs font-bold uppercase tracking-wider">Deskripsi Produk</th>
                    <th class="py-3 text-xs font-bold uppercase tracking-wider text-center">Qty</th>
                    <th class="py-3 text-xs font-bold uppercase tracking-wider text-right">Harga</th>
                    <th class="py-3 text-xs font-bold uppercase tracking-wider text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr class="border-b border-gray-200">
                    <td class="py-4">
                        <p class="font-bold text-sm uppercase">{{ $item->variant->product->name }}</p>
                        <p class="text-xs text-gray-500">Varian: {{ $item->variant->size }} / {{ $item->variant->color }}</p>
                    </td>
                    <td class="py-4 text-center text-sm font-medium">{{ $item->quantity }}</td>
                    <td class="py-4 text-right text-sm">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td class="py-4 text-right text-sm font-bold">Rp {{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Ringkasan Total -->
        <div class="flex justify-end">
            <div class="w-1/2 md:w-1/3">
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-sm text-gray-500">Subtotal</span>
                    <span class="text-sm font-medium">Rp {{ number_format($order->grand_total - $order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-black">
                    <span class="text-sm text-gray-500">Ongkos Kirim</span>
                    <span class="text-sm font-medium">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-4">
                    <span class="text-base font-bold uppercase tracking-wider">Total Akhir</span>
                    <span class="text-lg font-black">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-200 text-right">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Status Pembayaran:</span>
                    
                    @if($order->status == 'cancelled')
                        <p class="text-sm font-black uppercase text-red-600">DIBATALKAN</p>
                    @else
                        <p class="text-sm font-black uppercase {{ in_array($order->status, ['processing', 'shipped', 'completed']) ? 'text-black' : 'text-gray-400' }}">
                            {{ in_array($order->status, ['processing', 'shipped', 'completed']) ? 'LUNAS' : 'PENDING' }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tombol Aksi (Hanya muncul di layar komputer, hilang saat dicetak) -->
        <div class="mt-12 text-center no-print border-t border-dashed border-gray-300 pt-8">
            <button onclick="window.print()" class="px-8 py-4 bg-black text-white text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition-colors mr-2">
                Cetak Invoice
            </button>
            <button onclick="window.close()" class="px-8 py-4 bg-white border border-gray-300 text-black text-xs font-bold uppercase tracking-widest hover:bg-gray-50 transition-colors">
                Tutup
            </button>
        </div>
    </div>

    <!-- Script agar jendela print otomatis muncul saat halaman ini dibuka -->
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>