<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// استدعاء الموديلات من مساراتها الصحيحة
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;

class OrderSeeder extends Seeder
{
    public function run()
    {
        // البحث عن الآدمن والمنتج الأول في القاعدة
        $admin = User::where('email', 'admin@edraakmc.com')->first();
        $product = Product::first();

        // التأكد من وجود بيانات قبل البدء
        if ($admin && $product) {
            // إنشاء الطلب العام
            $order = Order::create([
                'user_id' => $admin->id,
                'total_price' => $product->price * 2,
                'status' => 'pending'
            ]);

            // إنشاء تفاصيل الطلب مع تثبيت السعر
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => 2,
                'price_at_purchase' => $product->price, // هذا هو السعر الذي لن يتغير أبداً
                'size' => 'L'
            ]);
        }
    }
}
