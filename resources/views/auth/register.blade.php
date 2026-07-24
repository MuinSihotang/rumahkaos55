<x-app-layout>
    <div class="bg-gray-50 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 selection:bg-black selection:text-white">
        <div class="max-w-md w-full space-y-8 bg-white p-10 border border-gray-200">
            <div>
                <h2 class="mt-2 text-center text-3xl font-extrabold text-black uppercase tracking-tight">
                    Create Account
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Daftar untuk mulai berbelanja
                </p>
            </div>
            
            <form class="mt-8 space-y-6" action="{{ route('register') }}" method="POST">
                @csrf
                
                @if ($errors->any())
                    <div class="bg-red-50 text-red-500 p-4 text-sm border border-red-200">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-black mb-1">Nama Lengkap</label>
                        <input id="name" name="name" type="text" required value="{{ old('name') }}" class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-400 text-black focus:outline-none focus:ring-black focus:border-black sm:text-sm transition-colors">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-black mb-1">Email Address</label>
                        <input id="email" name="email" type="email" required value="{{ old('email') }}" class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-400 text-black focus:outline-none focus:ring-black focus:border-black sm:text-sm transition-colors">
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-black mb-1">Password</label>
                        <input id="password" name="password" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-400 text-black focus:outline-none focus:ring-black focus:border-black sm:text-sm transition-colors">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-black mb-1">Konfirmasi Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-400 text-black focus:outline-none focus:ring-black focus:border-black sm:text-sm transition-colors">
                    </div>
                </div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-bold uppercase tracking-widest text-white bg-black hover:bg-gray-800 transition-colors">
                        Sign Up
                    </button>
                </div>
                
                <div class="text-center text-sm mt-4">
                    <span class="text-gray-500">Sudah punya akun?</span>
                    <a href="{{ route('login') }}" class="font-bold text-black hover:underline transition-colors ml-1">Masuk di sini</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>