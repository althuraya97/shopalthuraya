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
        // 1. حساب الآدمن (تم حذف حقل 'name' المسبب للخطأ)
        User::updateOrCreate(
            ['email' => 'admin@edraakmc.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => Hash::make('12345678'),
                'is_admin' => 1,
            ]
        );

        // 2. الأقسام والمنتجات (تأكد أن جدول الأقسام يحتوي على 'name')
        $categories = ['رجالي', 'نسائي', 'أطفال'];

        foreach ($categories as $catName) {
            $parent = Category::updateOrCreate(['name' => $catName]);

            for ($i = 1; $i <= 3; $i++) {
                Product::updateOrCreate(
                    ['slug' => Str::slug("prod-$catName-$i")],
                    [
                        'name' => "منتج $catName $i",
                        'description' => "وصف تجريبي للمنتج $i في قسم $catName",
                        'price' => rand(50, 200),
                        'category_id' => $parent->id,
                        'stock' => 50,
                        'image' => 'default.jpg',
                        'is_archived' => false,
                    ]
                );
            }
        }
    }
}
