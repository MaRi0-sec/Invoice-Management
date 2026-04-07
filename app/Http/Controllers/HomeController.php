<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use ArielMejiaDev\LarapexCharts\LarapexChart;

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

        $chart = (new LarapexChart)->donutChart()
            ->setTitle('الفواتير')
            ->addData([$invoicesPayed, $invoicesUnPayed, $invoicesPartial])
            ->setLabels(['مدفوع', 'غير مدفوع', 'مدفوع جزئيا'])
            ->setColors(['#1cc739', '#dc3545', '#eca407'])
            ->setHeight(350);
        $chart2 = (new LarapexChart)->barChart()
            ->setTitle('إحصائيات الفواتير')
            // أضفنا البيانات كلها في مصفوفة واحدة لمجموعة واحدة باسم "الإجمالي"
            ->addData([$invoicesPayed, $invoicesUnPayed, $invoicesPartial])
            // هنا نحدد مسميات كل عمود تحت في المحور الأفقي
            ->setXAxis(['مدفوع', 'غير مدفوع', 'مدفوع جزئيا'])
            ->setHeight(339);
        return view('home', compact(
            'invoices',
            'sumTotal',
            'invoicesUnPayed',
            'sumTotalUnPayed',
            'totalUnPayedRate',
            'invoicesPayed',
            'sumTotalPayed',
            'totalPayedRate',
            'invoicesPartial',
            'sumInvoicesPartial',
            'totalInvoicesPartialRate',
            'sumInvoicesPartialAmountPaid',
            'chart',
            'chart2'
        ));
    }
}
