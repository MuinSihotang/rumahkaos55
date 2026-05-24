<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_number', 'status', 'shipping_cost', 'grand_total', 'shipping_address', 'tracking_number'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    protected static function booted()
    {
        // Event 'updated' akan menyala jika ada perubahan data pada pesanan
        static::updated(function ($order) {
            // Cek apakah kolom 'status' berubah, DAN berubahnya menjadi 'cancelled'
            if ($order->isDirty('status') && $order->status === 'cancelled') {
                
                // Looping semua baju yang ada di dalam pesanan tersebut
                foreach ($order->items as $item) {
                    // Kembalikan stoknya
                    $item->variant()->increment('stock', $item->quantity);
                }
            }
        });
    }
}