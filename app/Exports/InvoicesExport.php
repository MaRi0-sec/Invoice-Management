<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;

class InvoicesExport implements FromCollection, WithMapping, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{

    private $i = 0;

    public function collection()
    {
        return Invoice::with(['section', 'product'])->get();
    }

    public function map($invoice): array
    {
        return
            [
                ++$this->i,
                $invoice->invoice_number,
                $invoice->invoice_date,
                $invoice->due_date,
                $invoice->section->section_name,
                $invoice->product->product_name,
                $invoice->amount_collection,
                $invoice->amount_commission,
                $invoice->discount,
                $invoice->value_vat,
                $invoice->rate_vat,
                $invoice->total,
                $invoice->total_with_value_vat,
                $invoice->amount_paid,
                $invoice->remaining_amount,
                $invoice->note
            ];
    }



    public function headings(): array
    {
        return [
            'م',
            'رقم الفاتورة',
            'تاريخ الفاتورة',
            'تاريخ الاستحقاق',
            'القسم',
            'المنتج',
            'مبلغ التحصيل',
            'مبلغ العمولة',
            'الخصم',
            'قيمة الضريبة',
            'نسبة الضريبة',
            'الإجمالي',
            'الإجمالي شامل الضريبة',
            'المبلغ المدفوع',
            'المبلغ المتبقي',
            'ملاحظات',
        ];
    }


    public function styles(Worksheet $sheet)
    {
        return [

            // تنسيق صف العناوين
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 14,
                    'color' => ['rgb' => 'FFFFFF'],
                ],

                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center',
                ],

                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => [
                        'rgb' => '4CAF50',
                    ],
                ],
            ],
        ];
    }



    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();

                $range = 'A2:' . $lastColumn . $lastRow;

                $sheet->getStyle($range)->applyFromArray([

                    'font' => [
                        'size' => 11,
                        'color' => ['rgb' => '000000'],
                    ],

                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center',
                    ],

                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => [
                            'rgb' => '9E9E9E',
                        ],
                    ],

                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],

                ]);
            },
        ];
    }
}
