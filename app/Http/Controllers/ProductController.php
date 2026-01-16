<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // جلب الأقسام الرئيسية لعرضها في الفلتر بشكل منظم
        $categories = Category::whereNull('parent_id')->with('children')->get();

        $query = Product::where('is_archived', false)
                        ->with(['category', 'subCategories']);

        // البحث بالاسم
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // التصفية حسب القسم المختار (الأساسي المرتبط بجدول المنتجات مباشرة)
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->latest()->paginate(15)->withQueryString();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        // جلب الأقسام الرئيسية مع أبنائها للقسم المرجعي الأساسي
        $categories = Category::whereNull('parent_id')->with('children')->get();

        // جلب الأقسام الفرعية من الموديل المستقل لغرض الـ Multi-select (الوسوم)
        $subCategories = SubCategory::all();

        return view('admin.products.create', compact('categories', 'subCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'sizes' => 'nullable|array',
            'category_id' => 'required|exists:categories,id', // القسم المرجعي الأساسي
            'sub_categories' => 'nullable|array',
            'sub_categories.*' => 'exists:sub_categories,id'
        ]);

        $imagePath = $request->file('image')->store('products', 'public');

        DB::transaction(function () use ($request, $imagePath) {
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'image' => $imagePath,
                'sizes' => $request->sizes,
                'category_id' => $request->category_id,
                'return_policy' => $request->return_policy,
                'is_archived' => false,
            ]);

            if ($request->filled('sub_categories')) {
                $product->subCategories()->attach($request->sub_categories);
            }
        });

        return redirect()->route('admin.products.index')->with('success', 'تم إنشاء المنتج بنجاح');
    }

    public function edit(Product $product)
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();
        $subCategories = SubCategory::all(); // جلب كافة الأقسام الفرعية لجدول الربط

        // شحن العلاقات للتأكد من المربعات المحددة (Checked) في Blade
        $product->load('subCategories');

        return view('admin.products.edit', compact('product', 'categories', 'subCategories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'category_id' => 'required|exists:categories,id',
            'sizes' => 'nullable|array',
            'sub_categories' => 'nullable|array',
            'sub_categories.*' => 'exists:sub_categories,id'
        ]);

        $data = $request->only(['name', 'description', 'price', 'sizes', 'category_id', 'return_policy']);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        DB::transaction(function () use ($product, $data, $request) {
            $product->update($data);
            // مزامنة الأقسام الفرعية في الجدول الوسيط
            $product->subCategories()->sync($request->sub_categories ?? []);
        });

        return redirect()->route('admin.products.index')->with('success', 'تم تحديث بيانات المنتج بنجاح');
    }

    public function destroy(Product $product)
    {
        // فحص وجود مبيعات سابقة
        $hasOrders = DB::table('order_items')->where('product_id', $product->id)->exists();

        if ($hasOrders) {
            $product->update(['is_archived' => true]);
            $message = 'تم أرشفة المنتج بدلاً من حذفه لارتباطه بطلبات سابقة.';
        } else {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->delete();
            $message = 'تم حذف المنتج نهائياً.';
        }

        return redirect()->route('admin.products.index')->with('success', $message);
    }

    public function toggleCategory(Request $request, Product $product)
    {
        $request->validate([
            'sub_category_id' => 'required|exists:sub_categories,id',
            'action' => 'required|in:attach,detach'
        ]);

        if ($request->action == 'attach') {
            $product->subCategories()->syncWithoutDetaching([$request->sub_category_id]);
            $msg = 'تم ربط القسم الفرعي بنجاح.';
        } else {
            $product->subCategories()->detach($request->sub_category_id);
            $msg = 'تم فك ارتباط القسم الفرعي.';
        }

        return back()->with('success', $msg);
    }
}
