<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class InvoicesStatuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $invoice = Invoice::find($this->invoice_id);

        return [
            'value_status'          => 'required|numeric',
            'invoice_id'            => 'required|exists:invoices,id',
            'invoice_number'        => 'required|exists:invoices,invoice_number',

            'payment_date'          =>
            [

                'nullable',          // لو مفيش قيمة مش هينفذ الاسطر الي بعديه هاااااااااااااااااام

                Rule::requiredIf(in_array($this->value_status, [Invoice::STATUS_PAID, Invoice::STATUS_PARTIAL])),
                'date_format:Y-m-d',
                'after_or_equal:' . $invoice->invoice_date,

            ],

            'note'                  => 'nullable|string|max:1000',

            'remaining_amount'      =>
            [
                'nullable',

                Rule::requiredIf($this->value_status == Invoice::STATUS_PARTIAL),
                'numeric',
                'min:0',
                'lte:' . $invoice->total_with_value_vat,
            ],

            'amount_paid'      =>
            [
                'nullable',

                Rule::requiredIf($this->value_status == Invoice::STATUS_PARTIAL),
                'numeric',
                'min:0',
                'lte:' . $invoice->total_with_value_vat,
            ]

        ];









        // ===============================================================================================================================================

        // $rules = [
        //     'value_status'                => 'required|numeric',
        //     'invoice_id'                  => 'required|exists:invoices,id',
        //     'invoice_number'              => 'required|exists:invoices,invoice_number',
        // ];


        // $invoice = Invoice::find($this->invoice_id);


        // if ($invoice) {
        //     if ($this->value_status == 1) {

        //         $rules['payment_date']  = 'required|date_format:Y-m-d|after_or_equal:' . $invoice->invoice_date;
        //         $rules['note']          = 'nullable|string|max:1000';
        //     } elseif ($this->value_status == 3) {

        //         $rules['payment_date']      = 'required|date_format:Y-m-d|after_or_equal:' . $invoice->invoice_date;
        //         $rules['remaining_amount']  = 'required|numeric|min:0|lte:' . $invoice->total_with_value_vat;
        //         $rules['amount_paid']       = 'required|numeric|min:0|lte:'   . $invoice->total_with_value_vat;
        //         $rules['note']              = 'nullable|string|max:1000';
        //     }
        // }

        // return $rules;
    }


    public function messages()
    {
        return [

            'invoice_id.required' => 'معرّف الفاتورة مطلوب.',
            'invoice_id.exists' => 'الفاتورة المحددة غير موجودة.',

            'invoice_number.required' => 'رقم الفاتورة مطلوب.',
            'invoice_number.exists' => 'رقم الفاتورة غير موجود.',

            'payment_date.required' => 'تاريخ الدفع مطلوب.',
            'payment_date.date' => 'تاريخ الدفع غير صالح.',
            'payment_date.date_format' => 'صيغة تاريخ الدفع يجب أن تكون YYYY-MM-DD.',
            'payment_date.after_or_equal' => 'تاريخ الدفع يجب أن يكون بعد أو يساوي تاريخ الفاتورة.',

            'remaining_amount.required' => 'المبلغ المتبقي مطلوب.',
            'remaining_amount.numeric' => 'المبلغ المتبقي يجب أن يكون رقمًا.',
            'remaining_amount.min' => 'المبلغ المتبقي يجب أن يكون أكبر من أو يساوي صفر.',
            'remaining_amount.lte' => 'المبلغ المتبقي لا يمكن أن يكون أكبر من إجمالي الفاتورة.',

            'amount_paid.required' => 'المبلغ المدفوع مطلوب.',
            'amount_paid.numeric' => 'المبلغ المدفوع يجب أن يكون رقمًا.',
            'amount_paid.min' => 'المبلغ المدفوع يجب أن يكون أكبر من أو يساوي صفر.',
            'amount_paid.lte' => 'المبلغ المدفوع لا يمكن أن يكون أكبر من إجمالي الفاتورة.',

            'value_status.required' => 'حالة الدفع مطلوبة.',
            'value_status.numeric' => 'حالة الدفع يجب أن تكون رقمًا.',

            'note.string' => 'الملاحظة يجب أن تكون نص.',
            'note.max' => 'الملاحظة لا يجب أن تتجاوز 1000 حرف.',

        ];
    }
}
