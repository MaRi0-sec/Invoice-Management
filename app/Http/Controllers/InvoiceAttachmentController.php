<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;



class InvoiceAttachmentController extends Controller
{


    public function __construct()
    {
        $this->middleware('permission:قائمة الفواتير')->only([
            'edit'
        ]);
    }

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
            session()->flash('error_if_exests', 'هذا المرفق موجود من قبل');
            return back();
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

            return back()->with('Add', 'تم اضافة المرفق');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ غير متوقع أثناء الرفع');
        }
    }


    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'file_id'           => 'required|integer|min:1|exists:invoice_attachments,id',
        ]);

        $file_id = $validated['file_id'];

        $attachment = InvoiceAttachment::findOrFail($file_id);

        $attachment->delete();

        session()->flash('delete', 'تم حذف المرفق');
        return back();
    }

    public function download_file($invoice_number, $file_name)
    {
        $file = public_path('Attachments/' . $invoice_number . '/' . $file_name);
        if (!$file) {
            abort(404, 'الملف غير موجود');
        }
        return response()->download($file);
    }
}
