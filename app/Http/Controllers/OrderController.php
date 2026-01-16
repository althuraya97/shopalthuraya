<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Mail\OrderShippedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * عرض قائمة الطلبات للآدمن (لوحة التحكم)
     */
    public function adminIndex(Request $request)
    {
        $query = Order::with(['user', 'items']);

        // البحث برقم الطلب
        if ($request->filled('search_id')) {
            $query->where('id', $request->search_id);
        }

        // الفلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * عرض قائمة الطلبات للزبون (المتجر)
     * معيار القبول: يجب أن يرى الزبون طلباته الخاصة فقط مع الترقيم (15 طلب)
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
                       ->latest()
                       ->paginate(15);

        return view('frontend.orders.index', compact('orders'));
    }

    /**
     * معالجة تأكيد الطلب وحفظ البيانات
     * معيار القبول: حماية من SQL Injection والتحقق من الحقول الإجبارية
     */
   public function store(Request $request) {
    $cart = session()->get('cart');

    // التأكد من وجود سلة
    if (!$cart || count($cart) == 0) {
        return redirect()->route('shop.index')->with('error', 'سلتك فارغة!');
    }

    // 1. حساب المجموع الكلي
    $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

    // 2. إنشاء سجل الطلب
    $order = \App\Models\Order::create([
        'user_id' => auth()->id(),
        'total_price' => $total,
        'status' => 'pending', // حالة الطلب الافتراضية
    ]);

    // 3. إنشاء سجلات المنتجات داخل الطلب
    foreach ($cart as $id => $details) {
        \App\Models\OrderItem::create([
            'order_id'   => $order->id,
            'product_id' => $details['id'],
            'quantity'   => $details['quantity'],
            'price'      => $details['price'],
            'size'       => $details['size'] ?? null,
        ]);
    }

    // 4. تفريغ السلة بعد نجاح الحفظ
    session()->forget('cart');

    return redirect()->route('orders.success', $order->id)
                     ->with('success', 'شكراً لك! تم استلام طلبك بنجاح.');
}

    /**
     * عرض تفاصيل طلب معين للزبون أو الآدمن
     */
    public function show(Order $order)
    {
        // معيار القبول: منع الزبون من رؤية طلبات الآخرين
        if (Auth::user()->role !== 'admin' && $order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.product', 'user']);

        // التحقق من مكان العرض (آدمن أم زبون)
        $view = Auth::user()->role === 'admin' ? 'admin.orders.show' : 'frontend.orders.show';
        return view($view, compact('order'));
    }

    /**
     * تحديث حالة الطلب (خاص بالآدمن)
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // إرسال بريد إلكتروني إذا تم الشحن
        if ($request->status == 'shipped' && $oldStatus != 'shipped') {
            try {
                Mail::to($order->user->email)->send(new OrderShippedMail($order));
            } catch (\Exception $e) {
                // الفشل في إرسال الإيميل لا ينبغي أن يعطل النظام
            }
        }

        return back()->with('success', 'تم تحديث حالة الطلب بنجاح.');
    }
}
