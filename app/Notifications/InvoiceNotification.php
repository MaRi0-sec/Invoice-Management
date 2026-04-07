<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Models\Invoice;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class InvoiceNotification extends Notification
{
    use Queueable;

    private $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->invoice_number,
            'auth_invoice' => $this->invoice->user->name,
            'message' => 'تم إضافة فاتورة جديدة برقم ' . $this->invoice->invoice_number,
        ];
    }
}
