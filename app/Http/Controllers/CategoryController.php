<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str; // تأكد من إضافة هذا السطر في أعلى الملف
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
    $category = Category::with(['children'])->findOrFail($id);
    $allIds = $category->children->pluck('id')->push($category->id);

    // بدلاً من التوقف، نقوم بنقل المنتجات إلى القسم رقم 1 (تأكد من وجود ID رقم 1)
    Product::whereIn('category_id', $allIds)->update(['category_id' => 1]);

    DB::transaction(function () use ($category) {
        $category->children()->delete();
        $category->delete();
    });

    return redirect()->route('admin.categories.index')->with('success', 'تم حذف القسم ونقل المنتجات إلى القسم العام.');
}
}
