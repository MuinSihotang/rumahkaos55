<x-app-layout>
    <div class="bg-gray-50 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-10 border border-gray-200 text-center">
            
            <svg class="mx-auto h-16 w-16 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            
            <h2 class="mt-6 text-2xl font-extrabold text-black uppercase tracking-tight">Pesanan Dibuat!</h2>
            <p class="mt-2 text-sm text-gray-500">Order ID: <span class="font-bold text-black">{{ $order->order_number }}</span></p>
            <p class="mt-1 text-sm text-gray-500">Silakan selesaikan pembayaran Anda untuk memproses pesanan.</p>

            <div class="mt-8">
                <button id="pay-button" class="w-full flex justify-center py-4 px-4 border border-transparent text-sm font-bold uppercase tracking-widest text-white bg-black hover:bg-gray-800 transition-colors">
                    Bayar Sekarang
                </button>
            </div>
        </div>
    </div>

    <!-- Injeksi Pustaka Midtrans Snap Client -->
    <!-- Penyesuaian URL Environment dari Sandbox menuju Production saat fase Deployment -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    // Redireksi ke antarmuka status transaksi berhasil
                    window.location.href = "/"; 
                },
                onPending: function(result){
                    alert("Menunggu pembayaran Anda!");
                },
                onError: function(result){
                    alert("Pembayaran gagal!");
                },
                onClose: function(){
                    alert('Anda menutup pop-up tanpa menyelesaikan pembayaran');
                }
            });
        };
    </script>
</x-app-layout>