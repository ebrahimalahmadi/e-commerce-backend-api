<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    use HasFactory;
    protected $table = 'products';
    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'featured',
        'active',
        'category_id',
        // 'image',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // للحصول على الصورة الرئيسية للمنتج
    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }
}
