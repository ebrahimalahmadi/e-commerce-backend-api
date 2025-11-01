<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    use HasFactory;

    protected $table = 'orders';

    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'subtotal',
        'shipping_cost',
        'tax_amount',
        'total',
        'notes'
    ];


    /**
     * the relationship between order and user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * the relationship between order and order item
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }




    /**
     * إنشاء رقم طلب تلقائي
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
            }
        });
    }

    /**
     * scope للطلبات النشطة
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }
    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }
    public function scopeShipped($query)
    {
        return $query->where('status', 'shipped');
    }
    public function scopeCompleted($query)
    {
        return $query->where('status', 'delivered');
    }
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * تحديث إجماليات الطلب
     */
    public function updateTotals(): void
    {
        $subtotal = $this->items->sum('total_price');
        $this->update([
            'subtotal' => $subtotal,
            'total' => $subtotal + $this->shipping_cost + $this->tax_amount,
        ]);
    }
}
