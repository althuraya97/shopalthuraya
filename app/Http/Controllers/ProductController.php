<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
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
        $categories = Category::whereNull('parent_id')->with('children')->get();

        $query = Product::where('is_archived', false)
                        ->with(['category', 'subCategories']);

        // التحديث: البحث بالاسم أو برقم المنتج (ID)
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('id', $searchTerm); // البحث بالرقم المباشر
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->latest()->paginate(15)->withQueryString();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();
        $subCategories = SubCategory::all();

        return view('admin.products.create', compact('categories', 'subCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0', // إضافة التحقق من المخزون
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'sizes' => 'nullable|array',
            'category_id' => 'required|exists:categories,id',
            'sub_categories' => 'nullable|array',
            'sub_categories.*' => 'exists:sub_categories,id'
        ]);

        $imagePath = $request->file('image')->store('products', 'public');
        $slug = Str::slug($request->name, '-', 'ar') ?: str_replace(' ', '-', $request->name) . '-' . time();

        DB::transaction(function () use ($request, $imagePath, $slug) {
            $product = Product::create([
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock, // حفظ كمية المخزون
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
        $subCategories = SubCategory::all();
        $product->load('subCategories');

        return view('admin.products.edit', compact('product', 'categories', 'subCategories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0', // إضافة المخزون في التحديث
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'category_id' => 'required|exists:categories,id',
            'sizes' => 'nullable|array',
            'sub_categories' => 'nullable|array',
            'sub_categories.*' => 'exists:sub_categories,id'
        ]);

        // إضافة stock للمصفوفة المراد تحديثها
        $data = $request->only(['name', 'description', 'price', 'stock', 'sizes', 'category_id', 'return_policy']);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        DB::transaction(function () use ($product, $data, $request) {
            $product->update($data);
            $product->subCategories()->sync($request->sub_categories ?? []);
        });

        return redirect()->route('admin.products.index')->with('success', 'تم تحديث بيانات المنتج بنجاح');
    }

    public function destroy(Product $product)
    {
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

    // تم تحديث هذه الدالة مسبقاً وهي تعمل بشكل صحيح
    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $product->update([
            'stock' => $request->stock
        ]);

        return back()->with('success', 'تم تحديث كمية المخزون بنجاح للمنتج: ' . $product->name);
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
