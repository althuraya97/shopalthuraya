<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. إنشاء حساب الآدمن
        User::updateOrCreate(
            ['email' => 'admin@edraak.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // 2. إنشاء الأقسام الرئيسية والفرعية
        $categories = [
            'رجالي' => ['أحذية', 'تيشيرتات', 'بناطيل'],
            'نسائي' => ['فساتين', 'حقائب', 'أحذية نسائية'],
            'أطفال' => ['ملابس أطفال', 'ألعاب'],
        ];

        foreach ($categories as $parentName => $subCats) {
            $parent = Category::create(['name' => $parentName]);

            foreach ($subCats as $subName) {
                $subCategory = Category::create([
                    'name' => $subName,
                    'parent_id' => $parent->id
                ]);

                // 3. إنشاء 5 منتجات لكل قسم فرعي
                for ($i = 1; $i <= 5; $i++) {
                    Product::create([
                        'name' => $subName . ' موديل ' . $i,
                        'slug' => Str::slug($subName . '-' . $i . '-' . Str::random(5)),
                        'description' => 'هذا وصف تجريبي لمنتج عالي الجودة من فئة ' . $subName,
                        'price' => rand(150, 900), // سعر عشوائي بين 150 و 900 ر.س
                        'category_id' => $parent->id, // القسم الرئيسي
                        'image' => 'products/default.jpg', // تأكد من وجود صورة افتراضية أو اتركها نصاً
                        'stock' => rand(10, 50),
                    ]);
                }
            }
        }
    }
}
