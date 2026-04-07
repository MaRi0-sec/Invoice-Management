<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;


class HomeController extends Controller
{

    public function index()
    {
        $invoices = Invoice::count();
        $sumTotal = Invoice::sum('total_with_value_vat');
        $invoicesUnPayed = Invoice::where('value_status', 2)->count();
        $sumTotalUnPayed = Invoice::where('value_status', 2)->sum('total_with_value_vat');
        $totalUnPayedRate = $invoices > 0 ? $invoicesUnPayed / $invoices * 100 : 0;
        $invoicesPayed = Invoice::where('value_status', 1)->count();
        $sumTotalPayed = Invoice::where('value_status', 1)->sum('total_with_value_vat');
        $totalPayedRate = $invoices > 0 ? $invoicesPayed / $invoices * 100 : 0;
        $invoicesPartial = Invoice::where('value_status', 3)->count();
        $sumInvoicesPartial = Invoice::where('value_status', 3)->sum('total_with_value_vat');
        $sumInvoicesPartialAmountPaid = Invoice::where('value_status', 3)->sum('amount_paid');
        $totalInvoicesPartialRate = $invoices > 0 ? $invoicesPartial / $invoices * 100 : 0;

        $data = [
            'status' => true,
            'message' => 'Home Statistics Retrieved Successfully',
            'statistics' => [
                'total_count' => $invoices,
                'total_sum' => number_format($sumTotal, 2),
                'unpaid' => [
                    'count' => $invoicesUnPayed,
                    'sum' => number_format($sumTotalUnPayed, 2),
                    'rate' => round($totalUnPayedRate, 2) . '%'
                ],
                'paid' => [
                    'count' => $invoicesPayed,
                    'sum' => number_format($sumTotalPayed, 2),
                    'rate' => round($totalPayedRate, 2) . '%'
                ],
                'partial' => [
                    'count' => $invoicesPartial,
                    'sum' => number_format($sumInvoicesPartial, 2),
                    'amount_paid' => number_format($sumInvoicesPartialAmountPaid, 2),
                    'rate' => round($totalInvoicesPartialRate, 2) . '%'
                ]
            ]
        ];
        return response()->json(['data' => $data], 200);
    }
}
