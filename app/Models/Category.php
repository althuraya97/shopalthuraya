<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'parent_id'];
   // الأقسام الفرعية التابعة لهذا القسم
public function children()
{
    return $this->hasMany(Category::class, 'parent_id');
}

// القسم الأب (في حال أردت استخدامه مستقبلاً)
public function parent()
{
    return $this->belongsTo(Category::class, 'parent_id');
}

// المنتجات التابعة للقسم
public function products()
{
    return $this->hasMany(Product::class);
}

}
