<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
protected $fillable = ['order_id', 'product_id', 'quantity', 'price', 'size'];
    /**
     * الحصول على الطلب الأصلي التابع له هذا الصنف
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * الحصول على بيانات المنتج الحالية (لجلب الاسم والصورة)
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
