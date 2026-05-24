<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Katalog Produk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Utama')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                        Forms\Components\TextInput::make('slug')
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->unique(Product::class, 'slug', ignoreRecord: true),
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable(),
                        Forms\Components\TextInput::make('base_price')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\RichEditor::make('description')
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                        
                        // --- UNGGAH GAMBAR UTAMA ---
                        Forms\Components\FileUpload::make('image_path')
                            ->label('Gambar Utama Produk')
                            ->image() // Memastikan hanya file gambar yang diterima
                            ->imageEditor() // Memberikan kemampuan cropping/editing
                            ->directory('product-images') // Menentukan folder penyimpanan di storage/app/public/product-images
                            ->columnSpanFull() // Membuatnya memenuhi lebar penuh section
                            // ->required() // Bisa opsional
                        // ----------------------------

                    ])->columns(2),

                // --- GALERI GAMBAR PRODUK (OPSIONAL) ---
                Forms\Components\Section::make('Galeri Gambar Produk (Detail/Tampilan Lain)')
                    ->schema([
                        // Repeater untuk galeri gambar multi-item
                        Forms\Components\Repeater::make('gallery')
                            ->label('Galeri Gambar')
                            ->schema([
                                // Setiap item di galeri hanyalah satu FileUpload
                                Forms\Components\FileUpload::make('path')
                                    ->label('File Gambar Galeri')
                                    ->image()
                                    ->imageEditor()
                                    ->directory('product-gallery') // Folder berbeda untuk galeri
                                    ->required(),
                            ])
                            ->reorderableWithDragAndDrop() // Fitur drag & drop untuk mengurutkan
                            ->addable() // Mengizinkan tambah item
                            ->deletable() // Mengizinkan hapus item
                            ->columns(1) // Tata letak satu kolom untuk schema Repeater
                            ->defaultItems(0) // Tidak ada item default, mulai dari kosong
                    ])
                    ->columnSpanFull(), // Taruh di section terpisah, penuhi lebar
                // ----------------------------

                // REPEATER UNTUK VARIAN T-SHIRT (UKURAN & WARNA)
                Forms\Components\Section::make('Varian Produk (Ukuran & Warna)')
                    ->schema([
                        Forms\Components\Repeater::make('variants')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('sku')
                                    ->label('SKU')
                                    ->required(),
                                Forms\Components\TextInput::make('color')
                                    ->label('Warna (Misal: Hitam)')
                                    ->required(),
                                Forms\Components\Select::make('size')
                                    ->label('Ukuran')
                                    ->options([
                                        'S' => 'Small (S)',
                                        'M' => 'Medium (M)',
                                        'L' => 'Large (L)',
                                        'XL' => 'Extra Large (XL)',
                                        'XXL' => 'Double XL (XXL)',
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('price')
                                    ->label('Harga Varian')
                                    ->numeric()
                                    ->required()
                                    ->prefix('Rp'),
                                Forms\Components\TextInput::make('stock')
                                    ->label('Stok')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),
                            ])
                            ->columns(5) // Membuat input berjejer ke samping agar rapi
                            ->defaultItems(1)
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('base_price')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_active'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}