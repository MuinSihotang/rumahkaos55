<?php

namespace App\Filament\Pages;

use App\Models\Order;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;

class LaporanPenjualan extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $title = 'Laporan Penjualan';
    protected static string $view = 'filament.pages.laporan-penjualan';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // PERUBAHAN UTAMA: Diperketat, HANYA mengambil orderan dengan status 'completed'
                Order::query()->where('status', 'completed')->orderBy('created_at', 'desc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('No. Invoice')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Transaksi')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Pelanggan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color('success'), // Otomatis selalu berwarna hijau karena statusnya pasti completed
                Tables\Columns\TextColumn::make('grand_total')
                    ->label('Total Pendapatan')
                    ->money('IDR')
                    ->summarize(
                        Tables\Columns\Summarizers\Sum::make()
                            ->label('TOTAL OMSET (REALIZABLE)')
                            ->money('IDR')
                    ),
            ])
            ->filters([
                // Filter Periode Cepat (Minggu/Bulan/Tahun ini)
                Tables\Filters\SelectFilter::make('periode')
                    ->label('Periode Cepat')
                    ->options([
                        'mingguan' => 'Minggu Ini',
                        'bulanan' => 'Bulan Ini',
                        'tahunan' => 'Tahun Ini',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when($data['value'], function (Builder $query, $value): Builder {
                            return match ($value) {
                                'mingguan' => $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]),
                                'bulanan' => $query->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year),
                                'tahunan' => $query->whereYear('created_at', Carbon::now()->year),
                                default => $query,
                            };
                        });
                    }),

                // Filter Kustom Rentang Tanggal
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('created_until')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'], fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date));
                    })
            ])
            // ->actions([
            //     Tables\Actions\Action::make('Detail')
            //         ->url(fn (Order $record): string => \App\Filament\Resources\OrderResource::getUrl('index'))
            //         ->icon('heroicon-m-eye')->color('info'),
            // ])
            ->paginated([10, 25, 50, 100])
            
            // ----- EXPORT EXCEL OTOMATIS IKUT TERBATASI 'COMPLETE' -----
            ->headerActions([
                ExportAction::make('export_excel')
                    ->label('Cetak ke Excel')
                    ->color('success')
                    ->icon('heroicon-o-document-arrow-down')
                    ->exports([
                        ExcelExport::make('Laporan-Penjualan-Selesai')
                            // ---> KUNCI PERBAIKAN: Paksa engine Excel untuk memfilter status 'completed' <---
                            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'completed'))
                            
                            ->withFilename('Laporan_Omset_Selesai_' . date('Y-m-d'))
                            ->withColumns([
                                Column::make('order_number')->heading('No. Invoice'),
                                Column::make('created_at')->heading('Tanggal Transaksi')->format('yyyy-mm-dd hh:mm'),
                                Column::make('user.name')->heading('Nama Pelanggan'),
                                Column::make('status')->heading('Status Pesanan'),
                                Column::make('shipping_cost')->heading('Ongkos Kirim')->format('"Rp"#,##0'),
                                Column::make('grand_total')->heading('Total Pendapatan (Omset)')->format('"Rp"#,##0'),
                            ])
                            ->askForFilename()
                            ->askForWriterType()
                    ])
            ]);
    }
}