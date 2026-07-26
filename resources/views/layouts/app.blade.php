<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RUMAH KAOS 55 | SIDIKALANG PRIDE</title>
    
    <!-- Integrasi Framework Utilitas: Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Ekstensi Konfigurasi Tema Tailwind: Definisi Palet Warna Spesifik Enterprise -->
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

    <!-- Dependensi Tipografi: Import Font Inter untuk Konsistensi Antarmuka -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white text-black antialiased">

    <!-- Render Komponen Antarmuka Navigasi Global -->
    <x-navbar />

    <!-- Kontainer Utama: Injeksi Konten Dinamis (Slot Render) -->
    <main class="min-h-screen">
        {{ $slot }}
    </main>

    <!-- Render Komponen Antarmuka Footer Global -->
    <x-footer />

</body>
</html>