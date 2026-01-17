<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. تعريف الأقسام الرئيسية والفرعية
        $data = [
            'الرجال' => ['عناية باللحية', 'عطور رجالية', 'شامبو وبلسم', 'عناية بالبشرة'],
            'النساء' => ['مكياج', 'عناية بالشعر', 'مرطبات بشرة', 'عطور نسائية'],
            'الأطفال' => ['شامبو لا دموع', 'بودرة أطفال', 'لوشن للأطفال', 'زيوت طبيعية'],
        ];

        foreach ($data as $mainCat => $subs) {
            // إنشاء القسم الرئيسي
            $category = Category::create([
                'name' => $mainCat,
                'slug' => Str::slug($mainCat, '-'),
            ]);

            foreach ($subs as $subName) {
                // إنشاء القسم الفرعي (الوسم)
                $subCategory = SubCategory::create([
                    'name' => $subName,
                    'category_id' => $category->id, // إذا كنت تربطهم مباشرة
                ]);

                // 2. إنشاء منتجات عشوائية لكل قسم فرعي
                for ($i = 1; $i <= 5; $i++) {
                    $product = Product::create([
                        'category_id' => $category->id,
                        'name' => "منتج $subName المميز $i",
                        'description' => "هذا الوصف لمنتج $subName، يوفر أفضل نتائج العناية والجودة العالية.",
                        'price' => rand(50, 500),
                        'sizes' => json_encode(['Small', 'Medium', 'Large']), // تخزين كـ JSON كما في Controller الخاص بك
                        'image' => null, // يمكنك وضع مسار صورة افتراضية هنا
                        'is_archived' => false,
                    ]);

                    // ربط المنتج بالقسم الفرعي (علاقة Many-to-Many)
                    $product->subCategories()->attach($subCategory->id);
                }
            }
        }
    }
}
