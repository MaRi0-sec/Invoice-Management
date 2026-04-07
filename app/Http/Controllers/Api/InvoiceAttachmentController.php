<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceAttachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceAttachmentController extends Controller
{

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id'    => 'required|integer|min:1|exists:invoices,id',
            'attachment'     => 'file|mimes:pdf,jpg,png,jpeg|max:2048',
        ]);

        $invoice = Invoice::findOrFail($request->invoice_id);
        $file = $request->file('attachment');
        $file_name = $file->getClientOriginalName();

        $exists = InvoiceAttachment::where('file_name', $file_name)->where('invoice_id', $invoice->id)->exists();

        if ($exists) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'This attachment already exists for this invoice.'
                ],
                422
            );
        }

        try {
            DB::transaction(function () use ($invoice, $file, $file_name) {

                InvoiceAttachment::create([
                    'file_name' => $file_name,
                    'invoice_number' => $invoice->invoice_number,
                    'created_by' => Auth::user()->name,
                    'invoice_id' => $invoice->id,
                ]);

                $file->storeAs($invoice->invoice_number, $file_name, 'upload_attachments');
            });

            return response()->json([
                'status' => true,
                'message' => 'Attachment added successfully.'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An unexpected error occurred during upload.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'file_id'           => 'required|integer|min:1|exists:invoice_attachments,id',
        ]);

        $file_id = $validated['file_id'];

        $attachment = InvoiceAttachment::findOrFail($file_id);

        if (!$attachment) {
            return response()->json([
                'status' => false,
                'message' => 'Attachment not found.'
            ], 404);
        }

        $attachment->delete();

        return response()->json([
            'status' => true,
            'message' => 'Attachment deleted successfully.'
        ], 200);
    }

    public function download_file($invoice_number, $file_name)
    {
        $file = public_path('Attachments/' . $invoice_number . '/' . $file_name);
        if (!$file) {
            return response()->json([
                'status' => false,
                'message' => 'The requested file does not exist on the server.'
            ], 404);
        }
        return response()->download($file);
    }
}
