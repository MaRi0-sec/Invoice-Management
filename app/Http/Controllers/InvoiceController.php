<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Http\Requests\InvoicesStatuRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Section;
use App\Models\Product;
use App\Models\InvoiceDetail;
use App\Models\InvoiceAttachment;
use App\Exports\InvoicesExport;
use Illuminate\Http\Request;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;
use App\Notifications\InvoiceNotification;

class InvoiceController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        // عرض الفواتير
        $this->middleware('permission:قائمة الفواتير')->only([
            'index',
            'paidInvoices',
            'unPaidInvoices',
            'partialInvoices'
        ]);

        // إضافة فاتورة
        $this->middleware('permission:اضافة فاتورة')->only([
            'create',
            'store'
        ]);

        // تعديل الفاتورة
        $this->middleware('permission:تعديل الفاتورة')->only([
            'edit',
            'update'
        ]);

        // حذف الفاتورة
        $this->middleware('permission:حذف الفاتورة')->only([
            'destroy'
        ]);

        // تغيير حالة الدفع
        $this->middleware('permission:تغير حالة الدفع')->only([
            'statusEdit',
            'statusUpdate'
        ]);

        // طباعة الفاتورة
        $this->middleware('permission:طباعةالفاتورة')->only([
            'printInvoices'
        ]);

        // تصدير Excel
        $this->middleware('permission:تصدير EXCEL')->only([
            'exportInvoices'
        ]);
    }

    public function index()
    {
        $invoices = Invoice::all();
        return view('pages.invoices.index', compact('invoices'));
    }

    public function create()
    {
        $sections = Section::all();
        return view('pages.invoices.create', compact('sections'));
    }

    public function store(StoreInvoiceRequest $request)
    {

        $validated = $request->validated();

        $discount = (float) ($validated['discount'] ?? 0);
        $rate_vat = (float) ($validated['rate_vat'] ?? 0);
        $amount_commission = (float) ($validated['amount_commission'] ?? 0);

        $total = $amount_commission - $discount;
        $value_vat = $total * ($rate_vat / 100);
        $total_with_value_vat = $total + $value_vat;

        try {
            return DB::transaction(function () use ($validated, $request, $total, $value_vat, $total_with_value_vat) {
                $invoice = Invoice::create(array_merge($validated, [
                    'value_status'                   => Invoice::STATUS_UNPAID, // استخدام الثوابت
                    'status'                         => 'غير مدفوع',
                    'total'                          => $total,
                    'value_vat'                      => $value_vat,
                    'total_with_value_vat'           => $total_with_value_vat,
                    'amount_paid'                    => '0',
                    'remaining_amount'               => $total_with_value_vat,
                    'user_id'                        => Auth::id(),
                ]));


                InvoiceDetail::create([
                    'invoice_id'                => $invoice->id,
                    'invoice_number'            => $invoice->invoice_number,
                    'product'                   => $invoice->product->product_name,
                    'section'                   => $invoice->section->section_name,
                    'amount_paid'               => $invoice->amount_paid,
                    'total_with_value_vat'      => $invoice->total_with_value_vat,
                    'remaining_amount'          => $invoice->remaining_amount,
                    'status'                    => $invoice->status,
                    'value_status'              => $invoice->value_status,
                    'note'                      => $invoice->note,
                    'user'                      => Auth::user()->name,
                ]);

                if ($request->hasFile('attachment')) {

                    $file = $request->file('attachment');
                    $fileName = $file->getClientOriginalName();
                    $invoiceNumber = $validated['invoice_number'];

                    $file->storeAs($invoiceNumber, $fileName, 'upload_attachments');

                    InvoiceAttachment::create([
                        'file_name'      => $fileName,
                        'invoice_number' => $invoice->invoice_number,
                        'created_by'     => Auth::user()->name,
                        'invoice_id'     => $invoice->id,
                    ]);
                }

                return back()->with('add', 'تم إضافة الفاتورة بنجاح');
                $user = User::first();

                $user->notify(new InvoiceNotification($invoice));
            });
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ غير متوقع أثناء الرفع');
        }
    }

    public function show(Request $request)
    {
        $request->validate([
            'radio' => 'required|in:1,2',
            'invoice_number' =>
            [
                'nullable',
                Rule::requiredIf($request->radio == 2),
                'exists:invoices,invoice_number'
            ],
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
        ], [
            'radio.required' => 'يجب اختيار نوع البحث',
            'radio.in' => 'قيمة الاختيار غير صحيحة',

            'invoice_number.required_if' => 'يجب إدخال رقم الفاتورة عند البحث برقم الفاتورة',
            'invoice_number.exists' => 'رقم الفاتورة غير موجود',

            'start_at.date' => 'تاريخ البداية غير صحيح',
            'end_at.date' => 'تاريخ النهاية غير صحيح',
            'end_at.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية',
        ]);



        if ($request->radio == 2) {

            $invoices = Invoice::where('invoice_number', $request->invoice_number)->get();
            return view('pages.invoices.index', compact('invoices'));
        } elseif ($request->radio == 1) {

            if ($request->value_status && $request->value_status != 0) {
                $invoices = Invoice::where('value_status', $request->value_status);
                if ($request->start_at && $request->end_at) {
                    $start = date('Y-m-d', strtotime($request->start_at));
                    $end = date('Y-m-d', strtotime($request->end_at));

                    $invoices->whereBetween('invoice_date', [$start, $end]);
                }
            }

            if ($request->value_status == 0) {
                $query = Invoice::query();

                if ($request->start_at && $request->end_at) {
                    $start = date('Y-m-d', strtotime($request->start_at));
                    $end = date('Y-m-d', strtotime($request->end_at));

                    $query->whereBetween('invoice_date', [$start, $end]);
                }

                $invoices = $query->get();

                return view('pages.invoices.index', compact('invoices'));
            }

            $invoices = $invoices->get();

            return view('pages.invoices.index', compact('invoices'));
        }
    }

    public function edit($id)
    {
        $invoices = Invoice::findOrFail($id);
        $sections = Section::all();
        return view('pages.invoices.edit', compact('invoices', 'sections'));
    }

    public function update(UpdateInvoiceRequest $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        $validated = $request->validated();

        return DB::transaction(function () use ($request, $invoice, $validated) {
            try {

                $invoice->update($validated);

                $invoice->details()->update([
                    'invoice_id'        => $invoice->id,
                    'invoice_number'    => $invoice->invoice_number,
                    'product'           => $invoice->product->product_name,
                    'section'           => $invoice->section->section_name,
                    'status'            => $invoice->status,
                    'value_status'      => $invoice->value_status,
                    'note'              => $invoice->note,
                    'user'              => Auth::user()->name,
                ]);
                return back()->with('edit', 'تم تعديل الفاتورة بنجاح');
            } catch (\Exception $e) {
                return back()->with('error', 'حدث خطأ غير متوقع أثناء الرفع');
            }
        });
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);

        $invoice->delete();

        return back()->with('delete_invoice', 'تم حذف الفاتورة');
    }

    public function statusEdit(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        return view('pages.invoices.status', compact('invoice'));
    }

    public function statusUpdate(InvoicesStatuRequest $request, InvoiceService $InvoiceService)
    {

        $invoice = Invoice::findOrFail($request->invoice_id);

        $InvoiceService->updateStatus($invoice, $request->validated());

        return back()->with('success', 'تم تحديث حالة الفاتورة');
    }

    public function exportInvoices()
    {
        return Excel::download(new InvoicesExport, 'Invoices.xlsx');
    }

    public function getProducts($section_id)
    {
        $product = Product::where('section_id', $section_id)->pluck('product_name', 'id');
        return response()->json($product);
    }

    public function partialInvoices()
    {
        $partialInvoices = Invoice::where('value_status', Invoice::STATUS_PARTIAL)->get();
        return view('pages.invoices.partial', compact('partialInvoices'));
    }

    public function printInvoices($id)
    {
        $invoices = Invoice::findOrFail($id);
        return view('pages.invoices.print', compact('invoices'));
    }
}
