<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubCategory extends Model
{
    protected $fillable = ['name', 'category_id'];

    /**
     * الحصول على القسم الأساسي الذي ينتمي إليه هذا القسم الفرعي
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
