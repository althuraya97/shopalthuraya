<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'image',
        'stock',         // تصحيح: اسم الحقل فقط هنا
        'sizes',
        'category_id',
        'return_policy',
        'is_archived',
    ];

    protected $casts = [
        'stock' => 'integer',      // التحديث: هنا يتم تعريف نوع البيانات
        'sizes' => 'array',
        'price' => 'decimal:2',
        'is_archived' => 'boolean',
    ];

    // علاقة المنتج بالقسم الرئيسي
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // علاقة المنتج بالأقسام الفرعية (الجدول الوسيط)
    public function subCategories()
    {
        return $this->belongsToMany(SubCategory::class, 'product_sub_category');
    }
}
