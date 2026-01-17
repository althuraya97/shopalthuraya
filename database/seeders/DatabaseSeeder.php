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
        $data = [
            'الرجال' => ['عناية باللحية', 'عطور رجالية', 'شامبو وبلسم', 'عناية بالبشرة'],
            'النساء' => ['مكياج', 'عناية بالشعر', 'مرطبات بشرة', 'عطور نسائية'],
            'الأطفال' => ['شامبو لا دموع', 'بودرة أطفال', 'لوشن للأطفال', 'زيوت طبيعية'],
        ];

        foreach ($data as $mainCat => $subs) {
            // إنشاء القسم الرئيسي
            $category = Category::create([
                'name' => $mainCat,
                'slug' => Str::slug($mainCat, '-', 'ar') ?: str_replace(' ', '-', $mainCat) . '-' . rand(10, 99),
            ]);

            foreach ($subs as $subName) {
                // إنشاء القسم الفرعي
                $subCategory = SubCategory::create([
                    'name' => $subName,
                    'category_id' => $category->id,
                ]);

                for ($i = 1; $i <= 5; $i++) {
                    $productName = "منتج $subName المميز $i";

                    $product = Product::create([
                        'category_id' => $category->id,
                        'name' => $productName,
                        // إضافة الـ slug للمنتج هنا لحل المشكلة
                        'slug' => Str::slug($productName, '-', 'ar') ?: str_replace(' ', '-', $productName) . '-' . rand(100, 999),
                        'description' => "وصف عالي الجودة لمنتج $subName للعناية الفائقة بمكونات طبيعية.",
                        'price' => rand(50, 500),
                        'sizes' => ['Small', 'Medium', 'Large'],
                        'image' => 'https://placehold.co/600x400?text=' . urlencode($subName),
                        'is_archived' => false,
                    ]);

                    // ربط المنتج بالقسم الفرعي
                    $product->subCategories()->attach($subCategory->id);
                }
            }
        }
    }
}
