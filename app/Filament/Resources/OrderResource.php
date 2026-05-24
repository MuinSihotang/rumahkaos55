<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\ProductVariant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Group;
use Filament\Support\Enums\MaxWidth;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Manajemen Pesanan';

    /**
     * FORM CONFIGURATION (CREATE & EDIT)
     */
    public static function form(Form $form): Form
    {
        // FUNGSI KALKULATOR OTOMATIS
        $updateTotal = function (Forms\Get $get, Forms\Set $set) {
            $items = $get('items') ?? [];
            $subtotal = 0;
            
            foreach ($items as $item) {
                $qty = (int) ($item['quantity'] ?? 0);
                $price = (float) ($item['unit_price'] ?? 0);
                $subtotal += ($qty * $price);
            }
            
            $shippingCost = (float) ($get('shipping_cost') ?? 0);
            $set('grand_total', $subtotal + $shippingCost);
        };

        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Informasi Pesanan')
                        ->schema([
                            Forms\Components\Select::make('user_id')
                                ->relationship('user', 'name')
                                ->label('Pelanggan')
                                ->required()
                                ->searchable(),
                            Forms\Components\TextInput::make('order_number')
                                ->label('Nomor Invoice')
                                ->default('INV-' . date('Ymd') . '-' . rand(100, 999))
                                ->required()
                                ->readOnly(),
                            Forms\Components\ToggleButtons::make('status')
                                ->options([
                                    'pending' => 'Pending',
                                    'processing' => 'Diproses',
                                    'shipped' => 'Dikirim',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan',
                                ])
                                ->colors([
                                    'pending' => 'warning',
                                    'processing' => 'info',
                                    'shipped' => 'success',
                                    'completed' => 'success',
                                    'cancelled' => 'danger',
                                ])
                                ->inline()
                                ->required()
                                ->default('pending'),
                        ])->columns(2),

                    Forms\Components\Section::make('Item Pesanan')
                        ->schema([
                            Forms\Components\Repeater::make('items')
                                ->relationship()
                                ->live() 
                                ->afterStateUpdated($updateTotal)
                                ->schema([
                                    Forms\Components\Select::make('product_variant_id')
                                        ->label('Pilih Varian T-Shirt (SKU)')
                                        ->options(ProductVariant::with('product')->get()->mapWithKeys(function ($variant) {
                                            return [$variant->id => $variant->product->name . ' - ' . $variant->color . ' (' . $variant->size . ')'];
                                        }))
                                        ->searchable()
                                        ->required()
                                        ->live()
                                        // PERBAIKAN BUG DISINI: Menambahkan Forms\Get $get ke dalam closure
                                        ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) use ($updateTotal) {
                                            $variant = ProductVariant::find($state);
                                            if ($variant) {
                                                $set('unit_price', $variant->price);
                                            }
                                            $updateTotal($get, $set);
                                        })
                                        ->columnSpan(2),
                                        
                                    Forms\Components\TextInput::make('quantity')
                                        ->label('Qty')
                                        ->numeric()
                                        ->default(1)
                                        ->required()
                                        ->live(onBlur: true) 
                                        ->afterStateUpdated($updateTotal)
                                        ->columnSpan(1),
                                        
                                    Forms\Components\TextInput::make('unit_price')
                                        ->label('Harga Satuan')
                                        ->numeric()
                                        ->required()
                                        ->readOnly()
                                        ->prefix('Rp')
                                        ->columnSpan(1),
                                ])->columns(4)
                        ]),
                ])->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Pengiriman & Tagihan')
                        ->schema([
                            Forms\Components\TextInput::make('shipping_cost')
                                ->label('Ongkos Kirim')
                                ->numeric()
                                ->default(0)
                                ->prefix('Rp')
                                ->live(onBlur: true)
                                ->afterStateUpdated($updateTotal),
                                
                            Forms\Components\TextInput::make('grand_total')
                                ->label('Total Keseluruhan')
                                ->numeric()
                                ->default(0)
                                ->prefix('Rp')
                                ->readOnly(), 
                                
                            Forms\Components\TextInput::make('tracking_number')
                                ->label('Nomor Resi'),
                            Forms\Components\Textarea::make('shipping_address')
                                ->label('Alamat Pengiriman')
                                ->required()
                                ->rows(3),
                        ])
                ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    /**
     * TABLE CONFIGURATION (LIST PESANAN)
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Nomor Order')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('grand_total')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'info',
                        'shipped' => 'success',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Pesan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalWidth(MaxWidth::SevenExtraLarge),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    /**
     * CUSTOM INFOLIST (MODAL VIEW)
     */
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Detail Pesanan Utama')
                            ->schema([
                                TextEntry::make('order_number')
                                    ->label('Nomor Invoice')
                                    ->weight('bold'),
                                TextEntry::make('created_at')
                                    ->label('Tanggal Transaksi')
                                    ->dateTime('d M Y H:i'),
                                TextEntry::make('status')
                                    ->label('Status Pesanan')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'pending' => 'warning',
                                        'processing' => 'info',
                                        'shipped' => 'success',
                                        'completed' => 'success',
                                        'cancelled' => 'danger',
                                    }),
                            ])->columns(3),

                        Section::make('Daftar T-Shirt Yang Dibeli')
                            ->schema([
                                RepeatableEntry::make('items')
                                    ->hiddenLabel() 
                                    ->schema([
                                        TextEntry::make('variant.product.name')
                                            ->label('Model T-Shirt')
                                            ->weight('bold')
                                            ->columnSpan(4),
                                            
                                        TextEntry::make('variant.color')
                                            ->label('Warna')
                                            ->columnSpan(1), 
                                            
                                        TextEntry::make('variant.size')
                                            ->label('Ukuran')
                                            ->badge()
                                            ->columnSpan(1), 
                                            
                                        TextEntry::make('unit_price')
                                            ->label('Harga Satuan')
                                            ->money('IDR')
                                            ->columnSpan(2), 
                                            
                                        TextEntry::make('quantity')
                                            ->label('Qty')
                                            ->columnSpan(1), 
                                            
                                        TextEntry::make('subtotal')
                                            ->label('Total Harga')
                                            ->money('IDR')
                                            ->state(fn ($record) => $record->quantity * $record->unit_price)
                                            ->weight('bold')
                                            ->color('primary')
                                            ->columnSpan(2), 
                                            
                                    ])->columns(11) 
                            ])
                    ])->columnSpan(['lg' => 2]),

                Group::make()
                    ->schema([
                        Section::make('Informasi Pengiriman')
                            ->schema([
                                TextEntry::make('user.name')
                                    ->label('Nama Penerima'),
                                TextEntry::make('tracking_number')
                                    ->label('Nomor Resi')
                                    ->placeholder('Belum Tersedia/Belum Dikirim')
                                    ->badge()
                                    ->color('info'),
                                TextEntry::make('shipping_address')
                                    ->label('Alamat Lengkap Tujuan')
                                    ->columnSpanFull(),
                            ]),

                        Section::make('Ringkasan Pembayaran')
                            ->schema([
                                TextEntry::make('shipping_cost')
                                    ->label('Ongkos Kirim')
                                    ->money('IDR'),
                                TextEntry::make('grand_total')
                                    ->label('Total Keseluruhan (Nett)')
                                    ->money('IDR')
                                    ->weight('bold')
                                    ->color('success')
                                    ->size('TextEntry\TextEntrySize::Large'),
                            ])
                    ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}