<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_variant_id', 'quantity', 'unit_price'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    protected static function booted()
    {
        // 1. Saat item baru ditambahkan
        static::created(function ($orderItem) {
            $orderItem->variant()->decrement('stock', $orderItem->quantity);
        });

        // 2. KUNCI PERBAIKAN: Saat item diedit (misal Qty dari 2 jadi 4)
        static::updated(function ($orderItem) {
            // Skenario A: Jika admin mengganti varian baju (misal dari Kaos Hitam ke Kaos Putih)
            if ($orderItem->isDirty('product_variant_id')) {
                // Kembalikan stok baju yang lama
                $oldVariant = ProductVariant::find($orderItem->getOriginal('product_variant_id'));
                if ($oldVariant) {
                    $oldVariant->increment('stock', $orderItem->getOriginal('quantity'));
                }
                // Potong stok baju yang baru
                $orderItem->variant()->decrement('stock', $orderItem->quantity);
            } 
            // Skenario B: Jika admin HANYA mengubah jumlah Qty (misal dari 2 jadi 4)
            elseif ($orderItem->isDirty('quantity')) {
                // Hitung selisihnya (4 - 2 = 2)
                $difference = $orderItem->quantity - $orderItem->getOriginal('quantity');
                // Decrement secara otomatis akan memotong jika positif, dan menambah jika negatif
                $orderItem->variant()->decrement('stock', $difference);
            }
        });

        // 3. Saat item dihapus dari pesanan
        static::deleted(function ($orderItem) {
            $orderItem->variant()->increment('stock', $orderItem->quantity);
        });
    }
}