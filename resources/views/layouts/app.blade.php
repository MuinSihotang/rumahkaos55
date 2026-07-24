<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>T-Shirt Co. | Enterprise E-Commerce</title>
    
    <!-- Menggunakan Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Konfigurasi tambahan untuk memastikan warna sesuai standar enterprise Anda (Opsional) -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#ffffff',
                        secondary: '#000000',
                        accent: '#9ca3af', // gray-400
                    }
                }
            }
        }
    </script>

    <!-- Font Inter untuk tampilan clean & modern -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white text-black antialiased">

    <!-- Memanggil Component Navbar -->
    <x-navbar />

    <!-- Konten Utama -->
    <main class="min-h-screen">
        {{ $slot }}
    </main>

    <!-- Memanggil Component Footer -->
    <x-footer />

</body>
</html>