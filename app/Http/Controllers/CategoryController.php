<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * عرض قائمة الأقسام الأساسية والفرعية بنظام الشجرة
     */
    public function index()
    {
        $categories = Category::whereNull('parent_id')
            ->with(['children', 'products'])
            ->withCount('products')
            ->latest()
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * عرض صفحة الإنشاء وتمرير الأقسام الرئيسية فقط لتكون "أب"
     */
    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('admin.categories.create', compact('parentCategories'));
    }

    /**
     * حفظ القسم (سواء كان رئيسياً أو فرعياً)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'parent_id' => 'nullable|exists:categories,id',
        ], [
            'name.unique' => 'هذا الاسم مستخدم بالفعل، يرجى اختيار اسم آخر.',
            'parent_id.exists' => 'القسم الرئيسي المختار غير موجود.'
        ]);

        Category::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'تم إضافة التصنيف بنجاح');
    }

    /**
     * عرض صفحة التعديل مع جلب الأقسام الرئيسية المقترحة كـ "أب"
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);

        // جلب الأقسام الرئيسية مع استثناء القسم الحالي وأبنائه لمنع حدوث حلقة مفرغة (Loop)
        $parentCategories = Category::whereNull('parent_id')
                            ->where('id', '!=', $id)
                            ->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * تحديث بيانات القسم
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'parent_id' => 'nullable|exists:categories,id|different:id',
        ], [
            'name.required' => 'يرجى إدخال اسم القسم',
            'name.unique' => 'هذا الاسم مستخدم بالفعل في قسم آخر',
            'parent_id.different' => 'لا يمكن للقسم أن يكون أباً لنفسه'
        ]);

        $category->update([
            'name' => $request->name,
            'parent_id' => $request->parent_id
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'تم تحديث بيانات القسم بنجاح');
    }

    /**
     * حذف القسم مع حماية المنتجات والأقسام الفرعية
     */
    public function destroy($id)
    {
        $category = Category::with(['children', 'products'])->findOrFail($id);

        // 1. فحص المنتجات المرتبطة (بالأب أو الأبناء)
        $childIds = $category->children->pluck('id');
        $hasProducts = $category->products()->exists() || Product::whereIn('category_id', $childIds)->exists();

        if ($hasProducts) {
            return back()->with('error', 'عذراً، لا يمكن حذف هذا التصنيف لوجود منتجات مرتبطة به مباشرة أو بأقسامه الفرعية.');
        }

        // 2. الحذف الآمن باستخدام Transaction
        DB::transaction(function () use ($category) {
            // حذف الأبناء أولاً
            $category->children()->delete();
            // حذف الأب
            $category->delete();
        });

        return redirect()->route('admin.categories.index')
                         ->with('success', 'تم حذف التصنيف وجميع الأقسام الفرعية التابعة له بنجاح.');
    }
}
