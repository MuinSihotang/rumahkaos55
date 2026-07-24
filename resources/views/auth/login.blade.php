<x-app-layout>
    <div class="bg-gray-50 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 selection:bg-black selection:text-white">
        <div class="max-w-md w-full space-y-8 bg-white p-10 border border-gray-200">
            <div>
                <h2 class="mt-2 text-center text-3xl font-extrabold text-black uppercase tracking-tight">
                    Sign In
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Masuk ke akun Anda untuk melanjutkan
                </p>
            </div>
            
            <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
                @csrf
                
                @if ($errors->any())
                    <div class="bg-red-50 text-red-500 p-4 text-sm border border-red-200">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-black mb-1">Email Address</label>
                        <input id="email" name="email" type="email" required value="{{ old('email') }}" class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-400 text-black focus:outline-none focus:ring-black focus:border-black sm:text-sm transition-colors">
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-black mb-1">Password</label>
                        <input id="password" name="password" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-400 text-black focus:outline-none focus:ring-black focus:border-black sm:text-sm transition-colors">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-black focus:ring-black border-gray-300 rounded-none cursor-pointer">
                        <label for="remember" class="ml-2 block text-sm text-gray-600 cursor-pointer">
                            Ingat saya
                        </label>
                    </div>
                    <div class="text-sm">
                        <a href="#" class="font-medium text-black hover:text-gray-500 transition-colors">Lupa password?</a>
                    </div>
                </div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-bold uppercase tracking-widest text-white bg-black hover:bg-gray-800 transition-colors">
                        Sign In
                    </button>
                </div>
                
                <div class="text-center text-sm mt-4">
                    <span class="text-gray-500">Belum punya akun?</span>
                    <a href="{{ route('register') }}" class="font-bold text-black hover:underline transition-colors ml-1">Buat Akun Sekarang</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>