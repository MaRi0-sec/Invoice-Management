<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\InvoiceAttachment;
use Illuminate\Support\Facades\Auth;

class InvoiceDetailController extends Controller
{

    public function index($id)
    {
        $invoice = Invoice::withTrashed()->where('id', $id)->first();

        if (!$invoice) {
            return response()->json(['message' => 'invoice not found'], 404);
        }

        $details = InvoiceDetail::where('invoice_id', $id)->get();

        $attachments = InvoiceAttachment::where('invoice_id', $id)->get();

        $userUnreadNotification = Auth::user()
            ->unreadNotifications->where('data.invoice_id', $id)->first();

        if ($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
        }

        $data = [$invoice, $details, $attachments];

        return response()->json(['data' => $data], 200);
    }
}
