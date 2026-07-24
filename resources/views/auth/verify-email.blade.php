<x-app-layout>
    <div class="bg-gray-50 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 selection:bg-black selection:text-white">
        <div class="max-w-md w-full space-y-8 bg-white p-10 border border-gray-200 text-center">
            
            <svg class="mx-auto h-16 w-16 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            
            <h2 class="mt-6 text-2xl font-extrabold text-black uppercase tracking-tight">Verifikasi Email Anda</h2>
            <p class="mt-2 text-sm text-gray-600">
                Terima kasih telah mendaftar! Sebelum mulai berbelanja, silakan klik tautan verifikasi yang baru saja kami kirimkan ke alamat email Anda.
            </p>

            @if (session('message'))
                <div class="bg-green-50 text-green-700 p-4 text-sm border border-green-200 mt-4">
                    {{ session('message') }}
                </div>
            @endif

            <div class="mt-8 space-y-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent text-sm font-bold uppercase tracking-widest text-white bg-black hover:bg-gray-800 transition-colors">
                        Kirim Ulang Email Verifikasi
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-sm font-medium text-gray-500 hover:text-black underline transition-colors">
                        Logout / Ganti Akun
                    </button>
                </form>
            </div>
            
        </div>
    </div>
</x-app-layout>