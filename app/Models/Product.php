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
    'stock',
    'category_id',
    'is_archived'
];

    // هنا تضع الكود الخاص بالـ casts
    protected $casts = [
        'sizes' => 'array', // لتحويل الحقل من JSON إلى مصفوفة PHP والعكس
        'is_archived' => 'boolean',
    ];

    /**
     * علاقة المنتج بالتصنيفات الفرعية (Many-to-Many)
     */
  public function subCategories()
{
    return $this->belongsToMany(
        SubCategory::class,    // الموديل المرتبط
        'product_subcategory', // اسم الجدول كما هو في الـ Migration الخاص بك
        'product_id',          // المفتاح الأجنبي للمنتج
        'sub_category_id'      // المفتاح الأجنبي للقسم الفرعي
    )->withTimestamps();
}
public function category()
{
    return $this->belongsTo(Category::class);
}
}
