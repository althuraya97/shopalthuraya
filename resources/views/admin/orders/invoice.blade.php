<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة طلب #{{ $order->id }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap');

        body { font-family: 'Cairo', sans-serif; background: #fdfdfd; color: #333; }
        .invoice-box {
            padding: 40px;
            border: 1px solid #eee;
            background: #fff;
            max-width: 850px;
            margin: 30px auto;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }
        .invoice-header { border-bottom: 3px solid #198754; margin-bottom: 20px; padding-bottom: 20px; }
        .table thead { background-color: #f8f9fa; }

        @media print {
            .no-print { display: none !important; }
            body { background: #fff; margin: 0; padding: 0; }
            .invoice-box { border: none; box-shadow: none; width: 100%; max-width: 100%; margin: 0; padding: 20px; }
            .container { width: 100% !important; max-width: 100% !important; }
        }
    </style>
</head>
<body>

<div class="container my-4">
    <div class="text-start no-print mb-4 d-flex justify-content-center gap-2">
        <button onclick="window.print()" class="btn btn-success shadow-sm px-4">
            <i class="fas fa-print"></i> طباعة الفاتورة (PDF)
        </button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary shadow-sm px-4">العودة للوحة التحكم</a>
    </div>

    <div class="invoice-box">
        <div class="invoice-header d-flex justify-content-between align-items-center">
            <div>
                <h2 class="text-success fw-bold mb-0">EdraakMC Store</h2>
                <p class="text-muted mb-0">متجرك الموثوق للأناقة</p>
            </div>
            <div class="text-end">
                <h4 class="fw-bold text-dark">فاتورة شراء</h4>
                <p class="mb-0 small text-muted">رقم الطلب: <strong>#{{ $order->id }}</strong></p>
                <p class="mb-0 small text-muted">تاريخ الإصدار: <strong>{{ $order->created_at->format('Y-m-d') }}</strong></p>
            </div>
        </div>

        <div class="row mb-5 mt-4">
            <div class="col-6">
                <h6 class="fw-bold text-success border-bottom pb-1 mb-2" style="width: fit-content;">العميل:</h6>
                <p class="mb-0 fw-bold">{{ $order->user->first_name }} {{ $order->user->last_name }}</p>
                <p class="mb-0 text-muted">{{ $order->user->email }}</p>
            </div>
            <div class="col-6 text-end">
                <h6 class="fw-bold text-success border-bottom pb-1 mb-2 ms-auto" style="width: fit-content;">عنوان الشحن:</h6>
                <p class="mb-0">{{ $order->country }}، {{ $order->city }}</p>
                <p class="mb-0">{{ $order->address }}</p>
                <p class="mb-0 small text-muted">طريقة الدفع: {{ $order->payment_method }}</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="text-center">
                    <tr>
                        <th class="text-start">المنتج</th>
                        <th>الحجم</th>
                        <th>الكمية</th>
                        <th>سعر الوحدة</th>
                        <th>الإجمالي</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="text-start fw-bold">{{ $item->product->name }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $item->size ?? '-' }}</span></td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price, 2) }} ر.س</td>
                        <td class="fw-bold">{{ number_format($item->price * $item->quantity, 2) }} ر.س</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row justify-content-end mt-4">
            <div class="col-md-5">
                <div class="d-flex justify-content-between p-2 border-bottom">
                    <span>المجموع الفرعي:</span>
                    <span>{{ number_format($order->total_price, 2) }} ر.س</span>
                </div>
                <div class="d-flex justify-content-between p-2 border-bottom">
                    <span>الشحن:</span>
                    <span class="text-success">مجاني</span>
                </div>
                <div class="d-flex justify-content-between p-2 bg-success text-white rounded mt-2">
                    <span class="fw-bold">المجموع الكلي:</span>
                    <span class="fw-bold fs-5">{{ number_format($order->total_price, 2) }} ر.س</span>
                </div>
            </div>
        </div>

        <div class="mt-5 pt-4 text-center border-top">
            <p class="text-muted small">شكراً لتسوقكم من EdraakMC Store! نأمل برؤيتكم مجدداً.</p>
            <p class="fw-bold text-success mb-0">www.edraakmc.com</p>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>
