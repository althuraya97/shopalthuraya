<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderShippedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * إنشاء نسخة جديدة من الرسالة وتمرير موديل الطلب
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * إعداد عنوان الرسالة (الظاهر في صندوق الوارد)
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'خبر سار! تم شحن طلبك رقم #' . $this->order->id,
        );
    }

    /**
     * تحديد القالب الذي سيتم استخدامه للرسالة
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order_shipped',
        );
    }
}
