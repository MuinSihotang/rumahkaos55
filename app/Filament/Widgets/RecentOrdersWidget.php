<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentOrdersWidget extends BaseWidget
{
    // Judul tabel
    protected static ?string $heading = 'Riwayat Penjualan Terbaru';
    
    // Urutan paling bawah (setelah 5 widget sebelumnya)
    protected static ?int $sort = 6;
    
    // Membuat tabel membentang penuh dari ujung kiri ke kanan
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            // Mengambil 5 pesanan terakhir yang masuk
            ->query(Order::query()->latest()->limit(5))
            ->columns([
                // Kolom 1: Order ID (Bisa dicopy dengan klik)
                Tables\Columns\TextColumn::make('id')
                    ->label('Order ID')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Order ID disalin!')
                    ->weight('bold'),

                // Kolom 2: Nama Pelanggan (Asumsi tabel orders berelasi ke tabel users)
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable(),

                // Kolom 3: Waktu Pesanan
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                // Kolom 4: Total Belanja
                Tables\Columns\TextColumn::make('grand_total')
                    ->label('Total Belanja')
                    ->money('IDR', locale: 'id') // Otomatis format ke Rupiah
                    ->sortable()
                    ->weight('bold'),

                // Kolom 5: Status dengan Badge Warna-warni
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',      // Kuning
                        'processing' => 'info',      // Biru
                        'shipped' => 'primary',      // Biru Gelap / Utama
                        'completed' => 'success',    // Hijau
                        'cancelled' => 'danger',     // Merah
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
            ])
            // Menghilangkan pagination karena kita hanya butuh 5 data terbaru di dashboard
            ->paginated(false); 
    }
}