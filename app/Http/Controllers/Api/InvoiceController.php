<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Section;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Http\Requests\InvoicesStatuRequest;
use App\Models\User;
use App\Models\Product;
use App\Models\InvoiceDetail;
use App\Models\InvoiceAttachment;
use App\Exports\InvoicesExport;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;
use App\Notifications\InvoiceNotification;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::latest()->paginate(10);

        return response()->json([
            'data' => $invoices
        ]);
    }

    public function getSections()
    {
        return response()->json(Section::all(), 200);
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
            $invoice = DB::Transaction(function () use ($validated, $request, $total, $value_vat, $total_with_value_vat) {
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
                    'invoice_id'        => $invoice->id,
                    'invoice_number'    => $invoice->invoice_number,
                    'product'           => $invoice->product->product_name,
                    'section'           => $invoice->section->section_name,
                    'status'            => $invoice->status,
                    'value_status'      => $invoice->value_status,
                    'note'              => $invoice->note,
                    'user'              => Auth::user()->name,
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

                $user = User::first();

                $user->notify(new InvoiceNotification($invoice));

                return $invoice;
            });
            return response()->json(
                [
                    'message' => 'Invoice created successfully',
                    'data' => $invoice
                ],
                '201'
            );
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong', 'error' => $e->getMessage(), 500]);
        }
    }


    public function search(Request $request)
    {
        $request->validate([
            'radio'             => 'required|in:1,2',
            'invoice_number'    =>
            [
                'nullable',
                Rule::requiredIf($request->radio == 2),
                'exists:invoices,invoice_number'
            ],
            'start_at'          => 'nullable|date',
            'end_at'            => 'nullable|date|after_or_equal:start_at',
        ], [
            'radio.required'    => 'يجب اختيار نوع البحث',
            'radio.in'          => 'قيمة الاختيار غير صحيحة',

            'invoice_number.required_if'    => 'يجب إدخال رقم الفاتورة عند البحث برقم الفاتورة',
            'invoice_number.exists'         => 'رقم الفاتورة غير موجود',

            'start_at.date'                 => 'تاريخ البداية غير صحيح',
            'end_at.date'                   => 'تاريخ النهاية غير صحيح',
            'end_at.after_or_equal'         => 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية',
        ]);



        if ($request->radio == 2) {

            $invoices = Invoice::where('invoice_number', $request->invoice_number)->get();
            if ($invoices->isNotEmpty()) {
                return response()->json(
                    [
                        'data'      => $invoices,
                    ],
                    200
                );
            }
            return response()->json(
                [
                    'message'      => 'not found',
                ],
                404
            );
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

                if ($invoices->isNotEmpty()) {
                    return response()->json(
                        [
                            'data'      => $invoices,
                        ],
                        200
                    );
                }
                return response()->json(
                    [
                        'message'      => 'not found',
                    ],
                    404
                );
            }

            $invoices = $invoices->get();

            if ($invoices->isNotEmpty()) {
                return response()->json(
                    [
                        'data'      => $invoices,
                    ],
                    200
                );
            }
            return response()->json(
                [
                    'message'      => 'not found',
                ],
                404
            );
        }
    }

    public function show($id)
    {
        $invoice = Invoice::with('details')->find($id);

        if (!$invoice) {
            return response()->json(['message' => 'غير موجود'], 404);
        }

        return response()->json([
            'data' => $invoice
        ]);
    }


    public function update(UpdateInvoiceRequest $request, $id)
    {
        $invoice = Invoice::find($id);

        if (!$invoice) {
            return response()->json(['message' => 'غير موجود'], 404);
        }

        $validated = $request->validated();

        try {
            DB::transaction(function () use ($invoice, $validated) {

                $invoice->update($validated);

                $invoice->details()->update([
                    'invoice_number' => $invoice->invoice_number,
                    'product'        => $invoice->product->product_name,
                    'section'        => $invoice->section->section_name,
                    'status'         => $invoice->status,
                    'value_status'   => $invoice->value_status,
                    'note'           => $invoice->note,
                    'user'           => Auth::user()->name,
                ]);
            });

            return response()->json([
                'message' => 'تم التحديث',
                'data' => $invoice
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'خطأ',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function updateStatus(InvoicesStatuRequest $request, InvoiceService $service)
    {
        $invoice = Invoice::find($request->invoice_id);

        if (!$invoice) {
            return response()->json(['message' => 'غير موجود'], 404);
        }

        $service->updateStatus($invoice, $request->validated());

        return response()->json([
            'message' => 'تم تحديث الحالة'
        ]);
    }


    public function getProducts($section_id)
    {
        $products = Product::where('section_id', $section_id)
            ->pluck('product_name', 'id');

        return response()->json($products);
    }


    public function partial()
    {
        return response()->json(
            Invoice::where('value_status', Invoice::STATUS_PARTIAL)->get()
        );
    }


    public function destroy($id)
    {
        $invoice = Invoice::find($id);

        if (!$invoice) {
            return response()->json(['message' => 'غير موجود'], 404);
        }

        $invoice->delete();

        return response()->json([
            'message' => 'تم الحذف'
        ]);
    }


    public function exportInvoices()
    {
        try {
            $fileName = 'Invoices_' . time() . '.xlsx';

            // 1. حفظ الملف في مجلد الـ public أو الـ storage
            Excel::store(new InvoicesExport, $fileName, 'public');

            // 2. تجهيز الرابط الكامل للملف
            $fileUrl = asset('storage/' . $fileName);

            return response()->json([
                'status' => true,
                'message' => 'Excel file generated successfully',
                'download_url' => $fileUrl
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to export Excel',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
