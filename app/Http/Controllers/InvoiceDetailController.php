<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\InvoiceAttachment;
use Illuminate\Support\Facades\Auth;


class InvoiceDetailController extends Controller
{
    public function edit($id)
    {
        $invoice = Invoice::withTrashed()->where('id', $id)->first();

        if (!$invoice) {
            abort(404);
        }

        $details = InvoiceDetail::where('invoice_id', $id)->get();

        $attachments = InvoiceAttachment::where('invoice_id', $id)->get();

        $userUnreadNotification = Auth::user()
            ->unreadNotifications->where('data.invoice_id', $id)->first();

        if ($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
        }

        return view('pages.invoices.show', compact('invoice', 'details', 'attachments'));
    }
}
