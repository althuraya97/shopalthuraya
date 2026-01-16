<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // 1. إنشاء أقسام رئيسية وفرعية
        $clothing = Category::create(['name' => 'ملابس']);
        $clothing->children()->create(['name' => 'رجالي']);
        $clothing->children()->create(['name' => 'نسائي']);

        $electronics = Category::create(['name' => 'إلكترونيات']);
        $electronics->children()->create(['name' => 'هواتف']);

        // 2. إنشاء 15 منتجاً تجريبياً
        for ($i = 1; $i <= 15; $i++) {
            Product::create([
                'name' => "منتج تجريبي رقم $i",
                'description' => "هذا وصف مفصل للمنتج رقم $i. يتميز بجودة عالية وتصميم عصري يناسب جميع الأذواق.",
                'price' => rand(100, 1000),
                'category_id' => $clothing->id,
                'image' => 'products/default.jpg', // تأكد من وجود صورة بهذا الاسم أو غيرها لاحقاً
                'sizes' => ['S', 'M', 'L', 'XL'],
                'return_policy' => 'يمكن استرجاع المنتج خلال 14 يوماً من تاريخ الشراء.',
                'is_archived' => false,
            ]);
        }
    }
}
