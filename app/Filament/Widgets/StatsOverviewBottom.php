<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class StatsOverviewBottom extends BaseWidget
{
    // Urutan di bawah widget baris pertama
    protected static ?int $sort = 2;

    // KUNCI LAYOUT: Mengubah grid menjadi 2 kolom agar card memanjang menyesuaikan layar
    protected function getColumns(): int
    {
        return 2;
    }

    protected function getStats(): array
    {
        // 4. Total Stok Tersedia di Gudang
        $totalStock = ProductVariant::sum('stock'); 

        // 5. Produk & Ukuran Paling Laris
        $topProduct = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->whereIn('orders.status', ['processing', 'shipped', 'completed'])
            ->select('products.name', 'product_variants.size', DB::raw('SUM(order_items.quantity) as total_qty'))
            ->groupBy('products.name', 'products.id', 'product_variants.size')
            ->orderByDesc('total_qty')
            ->first();

        return [
            Stat::make('Total Stok Tersedia', number_format($totalStock, 0, ',', '.'))
                ->description('Keseluruhan stok di gudang saat ini')
                ->descriptionIcon('heroicon-m-cube')
                ->color('info'),
                
            Stat::make('Produk Terlaris', $topProduct ? $topProduct->name . ' (Size ' . $topProduct->size . ')' : 'Belum Ada')
                ->description($topProduct ? 'Terjual sebanyak ' . $topProduct->total_qty . ' item' : 'Menunggu pesanan masuk')
                ->descriptionIcon('heroicon-m-fire')
                ->color('danger'),
        ];
    }
}