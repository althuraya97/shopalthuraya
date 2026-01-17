<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
// App\Models\Order.php
protected $fillable = [
    'user_id', 'total_price', 'status',
    'address', 'apartment', 'city', 'state', 'country', 'zip_code', 'payment_method'
];    /**
     * الحصول على الزبون الذي صاحب الطلب
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * الحصول على جميع المنتجات (الأصناف) داخل هذا الطلب
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
