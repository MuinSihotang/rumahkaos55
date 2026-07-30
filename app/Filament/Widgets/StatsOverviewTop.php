<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatsOverviewTop extends BaseWidget
{
    // Urutan paling atas
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // 1. Pesanan Menunggu Diproses
        $pendingOrders = Order::where('status', 'pending')->count();
        
        // 2. Pendapatan Bulan Ini
        $revenueThisMonth = Order::whereIn('status', ['processing', 'shipped', 'completed'])
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('grand_total');

        // 3. Total Barang Terjual (Sukses)
        $totalSold = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['processing', 'shipped', 'completed'])
            ->sum('order_items.quantity');

        return [
            Stat::make('Pesanan Menunggu', $pendingOrders)
                ->description('Perlu diproses segera')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
                
            Stat::make('Pendapatan (Bulan Ini)', 'Rp ' . number_format($revenueThisMonth, 0, ',', '.'))
                ->description('Total omzet kotor bulan ini')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
                
            Stat::make('Total Barang Terjual', number_format($totalSold, 0, ',', '.'))
                ->description('Produk lunas dan diproses')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('primary'),
        ];
    }
}