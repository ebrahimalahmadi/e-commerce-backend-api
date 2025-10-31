<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            // 
            'id'          => $this->id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'description' => $this->description,
            'price'       => $this->price,
            'stock'       => $this->stock,

            // 
            'active'      => $this->active,
            'featured'    => $this->featured,

            // إذا وُجدت صورة رئيسية (is_primary = true) يتم عرض الرابط 
            'primary_image_url' => $this->when(
                $this->relationLoaded('images') && $this->images->where('is_primary', true)->first(),
                fn() => asset('storage/' . $this->images->where('is_primary', true)->first()->path)
            ),

            // 
            'images'      => ProductImageResource::collection($this->whenLoaded('images')),

            // 
            'category'    => CategoryResource::make($this->whenLoaded('category')),
            'category_id' => $this->category_id,

            // 
            'created_at'  => $this->created_at->format('Y-m-d'),
            'updated_at'  => $this->updated_at->format('Y-m-d'),
        ];
    }
}

// -------------------------Notes-------------------------
// relationLoaded('images'): يتأكد أن علاقة الصور تم تحميلها (حتى لا تنفّذ استعلام إضافي).

// where('is_primary', true)->first(): يبحث عن الصورة الأساسية.

// asset('storage/...'): يُنشئ رابط الصورة الكامل.

// when(...): يعرض الحقل فقط إذا وُجدت صورة أساسية.