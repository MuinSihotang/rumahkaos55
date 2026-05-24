<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'base_price',
        'image_path',
        'gallery',
        'is_active',
    ];

    // Casting JSON untuk kolom gallery agar bisa diolah sebagai array
    protected $casts = [
        'gallery' => 'array', // Menangani encoding/decoding JSON ke array secara otomatis
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}