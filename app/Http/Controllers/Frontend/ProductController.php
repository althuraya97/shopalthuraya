<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category; // أضفنا موديل الأقسام
use Illuminate\Http\Request;

class ProductController extends Controller
{
   public function index(Request $request)
{
    // البدء باستعلام المنتجات غير المؤرشفة
    $query = Product::where('is_archived', false);

if ($request->filled('sub_category')) {
    // إذا كان القسم الفرعي مخزناً في نفس جدول الأقسام عبر parent_id
    $query->where('category_id', $request->sub_category);
}

    // 1. البحث بالاسم (كلي أو جزئي)
    if ($request->filled('search')) {
        $query->where('name', 'LIKE', '%' . $request->search . '%');
    }

    // 2. التصفية حسب القسم الرئيسي
    if ($request->filled('category')) {
        $query->where('category_id', $request->category);
    }

    // 3. التصفية حسب القسم الفرعي (علاقة Many-to-Many)
    if ($request->filled('sub_category')) {
        $query->whereHas('subCategories', function($q) use ($request) {
            $q->where('sub_categories.id', $request->sub_category);
        });
    }

    // 4. التصفية حسب السعر (أدنى وأعلى)
    if ($request->filled('min_price')) {
        $query->where('price', '>=', $request->min_price);
    }
    if ($request->filled('max_price')) {
        $query->where('price', '<=', $request->max_price);
    }

    // 5. التصفية حسب المقاس (داخل حقل JSON sizes)
    if ($request->filled('size')) {
        $query->whereJsonContains('sizes', $request->size);
    }

    // ميزة الترتيب (Sorting)
    switch ($request->sort) {
        case 'price_low':
            $query->orderBy('price', 'asc');
            break;
        case 'price_high':
            $query->orderBy('price', 'desc');
            break;
        case 'oldest':
            $query->orderBy('created_at', 'asc');
            break;
        default:
            $query->latest(); // الأحدث افتراضياً
            break;
    }

    $products = $query->paginate(15)->withQueryString();
    $categories = Category::with('children')->get();

return view('frontend.products.index', compact('products', 'categories'));
}

public function show($id)
{
    // جلب المنتج مع التأكد أنه ليس مؤرشفاً، أو رمي خطأ 404 إذا لم يوجد
    $product = Product::where('is_archived', false)->findOrFail($id);

    return view('frontend.products.show', compact('product'));

    }
}
