<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SizeDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'Persentase Ukuran Paling Diminati';
    protected static ?int $sort = 5;
    
    // Bikin membentang penuh (2 kolom)
    protected int | string | array $columnSpan = 'full';
    
    // KUNCI: Batasi tinggi maksimal agar tidak raksasa/lebar ke bawah
    protected static ?string $maxHeight = '280px'; 

    protected function getData(): array
    {
        $sizeData = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->whereIn('orders.status', ['processing', 'shipped', 'completed'])
            ->select('product_variants.size', DB::raw('SUM(order_items.quantity) as total_qty'))
            ->groupBy('product_variants.size')
            ->orderByDesc('total_qty')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Terjual',
                    'data' => $sizeData->pluck('total_qty')->toArray(),
                    'backgroundColor' => [
                        '#3b82f6', // Biru
                        '#ef4444', // Merah
                        '#10b981', // Hijau
                        '#f59e0b', // Kuning
                        '#8b5cf6', // Ungu
                        '#64748b', // Abu-abu
                    ],
                ],
            ],
            'labels' => $sizeData->pluck('size')->toArray(),
        ];
    }

    protected function getType(): string
    {
        // Donat chart sangat bagus untuk melihat persentase
        return 'doughnut';
    }
    
    // Opsional: Matikan aspect ratio bawaan Chart.js agar mengikuti maxHeight kita
    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
        ];
    }
}