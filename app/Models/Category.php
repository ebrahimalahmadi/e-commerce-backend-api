<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
    ];

    // i want use the slug as route key
    // Using Slug Instead of ID in Laravel Routes
    // public function getRouteKeyName()
    // {
    //     return 'slug';
    // }
}
