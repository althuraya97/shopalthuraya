<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; }
        .container { width: 90%; max-width: 600px; margin: 20px auto; border: 1px solid #eee; padding: 20px; border-radius: 10px; }
        .header { text-align: center; border-bottom: 2px solid #3490dc; padding-bottom: 10px; }
        .order-info { background: #f8fafc; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .footer { text-align: center; font-size: 12px; color: #777; margin-top: 30px; }
        .button { display: inline-block; padding: 10px 20px; background-color: #3490dc; color: #fff; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>مرحباً {{ $order->user->first_name }}</h2>
        </div>
        <p>نود إعلامك بأن طلبك أصبح في الطريق إليك الآن!</p>

        <div class="order-info">
            <strong>رقم الطلب:</strong> #{{ $order->id }}<br>
            <strong>حالة الطلب:</strong> تم الشحن<br>
            <strong>العنوان:</strong> {{ $order->city }}، {{ $order->address }}
        </div>

        <p>يمكنك متابعة حالة طلبك وتفاصيله من خلال حسابك في موقعنا:</p>
        <p style="text-align: center;">
            <a href="{{ route('orders.index') }}" class="button">عرض تفاصيل الطلب</a>
        </p>

        <p>شكراً لتسوقك معنا!</p>

        <div class="footer">
            جميع الحقوق محفوظة &copy; {{ date('Y') }} إدراك مول
        </div>
    </div>
</body>
</html>
