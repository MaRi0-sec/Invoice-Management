<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceAttachment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;




class InvoiceArchiveController extends Controller
{

    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('permission:قائمة الفواتير')->only('index');
        $this->middleware('permission:ارجاع الفاتوره')->only('restore');
        $this->middleware('permission:حذف الفاتورة')->only('destroy');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::onlyTrashed()->get();
        return view('pages.invoices.archive', compact('invoices'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($invoice_id)
    {
        $invoice = Invoice::onlyTrashed()->where('id', $invoice_id)->first();

        if (InvoiceAttachment::where('invoice_id', $invoice_id)->where('invoice_number', $invoice->invoice_number)->exists()) {
            Storage::disk('upload_attachments')->deleteDirectory($invoice->invoice_number);
        }

        $invoice->forcedelete();

        return back()->with('delete_invoice', 'تم حذف الفاتورة');
    }

    public function restore($invoice_id)
    {
        $invoice = Invoice::onlyTrashed()->where('id', $invoice_id)->first();

        $this->authorize('restore', $invoice);

        $invoice->restore();

        return back()->with('restore_invoice', 'تم حذف الفاتورة');
    }
}
