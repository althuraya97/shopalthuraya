<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // 1. استخدام Eager Loading لتحميل العلاقات بكفاءة
        // ملاحظة: تأكد أن اسم العلاقة في الموديل هو subCategories وليس sub_categories
        $query = Product::with(['category', 'subCategories'])
                        ->where('is_archived', false);

        // 2. البحث بالاسم أو الوصف
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('description', 'LIKE', '%' . $request->search . '%');
            });
        }

        // 3. التصفية حسب القسم الرئيسي
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // 4. التصفية حسب القسم الفرعي (علاقة Many-to-Many)
        if ($request->filled('sub_category')) {
            $query->whereHas('subCategories', function($q) use ($request) {
                // استخدام id بدون تحديد اسم الجدول إذا لم يكن هناك تعارض،
                // أو التأكد من اسم الجدول في قاعدة البيانات (غالباً sub_categories)
                $q->where('categories.id', $request->sub_category);
            });
        }

        // 5. التصفية حسب السعر
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // 6. التصفية حسب المقاس (JSON)
        // ملاحظة: تأكد أن حقل sizes في قاعدة البيانات نوعه JSON أو تم عمل Cast له كمصفوفة في الموديل
        if ($request->filled('size')) {
            $query->whereJsonContains('sizes', $request->size);
        }

        // 7. ميزة الترتيب (Sorting)
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

        // تنفيذ الاستعلام مع الحفاظ على الفلاتر في روابط الترقيم
        $products = $query->paginate(12)->withQueryString();

        // جلب الأقسام الرئيسية مع أبنائها لعرضها في الفلتر الجانبي
        $categories = Category::whereNull('parent_id')
                              ->with('children')
                              ->get();

        return view('frontend.products.index', compact('products', 'categories'));
    }

    public function show($id)
    {
        // جلب المنتج مع العلاقات الضرورية، واستخدام findOrFail لرمي خطأ 404 إذا لم يوجد
        $product = Product::with(['category', 'subCategories'])
                          ->where('is_archived', false)
                          ->findOrFail($id);

        return view('frontend.products.show', compact('product'));
    }
}
