<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceAttachment;
use Illuminate\Support\Facades\Storage;


class InvoiceArchiveController extends Controller
{

    public function index()
    {
        $invoices = Invoice::onlyTrashed()->get();
        return response()->json([
            'status' => true,
            'message' => 'Archived invoices retrieved successfully',
            'data' => $invoices
        ], 200);
    }

    public function destroy($invoice_id)
    {
        $invoice = Invoice::onlyTrashed()->where('id', $invoice_id)->first();

        if (!$invoice) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Invoice not found in archive'
                ],
                404
            );
        }

        if (InvoiceAttachment::where('invoice_id', $invoice_id)->where('invoice_number', $invoice->invoice_number)->exists()) {
            Storage::disk('upload_attachments')->deleteDirectory($invoice->invoice_number);
        }
        $invoice->forcedelete();
        return response()->json([
            'status' => true,
            'message' => 'Invoice and its attachments deleted permanently'
        ], 200);
    }

    public function restore($invoice_id)
    {
        $invoice = Invoice::onlyTrashed()->where('id', $invoice_id)->first();

        if (!$invoice) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Invoice not found in archive'
                ],
                404
            );
        }

        $invoice->restore();

        return response()->json([
            'status' => true,
            'message' => 'Invoice restored successfully'
        ], 200);
    }
}
