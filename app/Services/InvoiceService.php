<?php

namespace App\Services;

use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;


class InvoiceService
{
    public function updateStatus(Invoice $invoice, array $data)
    {

        $amount_paid = $data['amount_paid'] ?? '0';

        if ($data['value_status'] == Invoice::STATUS_PAID || $invoice->total_with_value_vat == $data['amount_paid']) {

            $value_status       = Invoice::STATUS_PAID;
            $status             = 'مدفوع';
            $amount_paid        = $invoice->total_with_value_vat;
            $remaining_amount   = 0;
        } elseif ($data['value_status'] == Invoice::STATUS_UNPAID) {

            $value_status       = Invoice::STATUS_UNPAID;
            $status             = 'غير مدفوع';
            $amount_paid        = 0;
            $remaining_amount   = $invoice->total_with_value_vat;
        } elseif ($data['value_status'] == Invoice::STATUS_PARTIAL) {

            $value_status       = Invoice::STATUS_PARTIAL;
            $status             = 'مدفوع جزئيا';
            $remaining_amount   = $invoice->remaining_amount - $data['amount_paid'];
            $amount_paid        = $data['amount_paid'] + $invoice->amount_paid;
        }
        if ($remaining_amount == 0) {

            $value_status       = Invoice::STATUS_PAID;
            $status             = 'مدفوع';
            $amount_paid        = $invoice->total_with_value_vat;
            $remaining_amount   = 0;
        }

        $invoice->update(array_merge($data, [
            'value_status'  => $value_status,
            'status'        => $status,
        ]));


        return $invoice->details()->create([
            'invoice_number'            => $invoice->invoice_number,
            'product'                   => $invoice->product->product_name,
            'section'                   => $invoice->section->section_name,
            'user'                      => Auth::user()->name ?? 'System',
            'remaining_amount'          => $remaining_amount,
            'amount_paid'               => $amount_paid,
            'value_status'              => $value_status,
            'status'                    => $status,
            'total_with_value_vat'      => $invoice->total_with_value_vat,
            'note'                      => $data['note'] ?? null,
            'payment_date'              => $data['payment_date'],
        ]);
    }
}
