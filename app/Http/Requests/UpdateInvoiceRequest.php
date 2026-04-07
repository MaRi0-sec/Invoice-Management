<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
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
        return [
            'invoice_id'                 => 'required|exists:invoices,id',
            'invoice_number'             => 'required|exists:invoices,invoice_number|max:50',
            'invoice_date'               => 'required|date|date_format:Y-m-d',
            'due_date'                   => 'required|date|date_format:Y-m-d|after_or_equal:invoice_date',
            'product_id'                 => 'required|exists:products,id',
            'section_id'                 => 'required|exists:sections,id',
            'amount_collection'          => 'required|numeric|min:0',
            'amount_commission'          => 'required|numeric|min:0',
            'discount'                   => 'required|numeric|min:0',
            'value_vat'                  => 'required|numeric',
            'rate_vat'                   => 'required|string|max:10',
            'total'                      => 'required|numeric',
            'total_with_value_vat'       => 'required|numeric',
            'note'                       => 'nullable|string|max: 1000',
        ];
    }
    public function messages(): array
    {
        return [
            'invoice_id.required'        => 'يرجى إدخال رقم الفاتورة.',
            'invoice_number.required'    => 'يرجى إدخال رقم الفاتورة.',
            'invoice_number.unique'      => 'رقم الفاتورة هذا مسجل مسبقاً، يرجى التأكد من الرقم.',
            'invoice_number.max'         => 'رقم الفاتورة طويل جداً، الحد الأقصى 50 حرفاً.',

            'invoice_date.required'      => 'تاريخ الفاتورة مطلوب.',
            'invoice_date.date'          => 'صيغة التاريخ غير صحيحة.',
            'invoice_date.date_format'   => 'يجب أن يكون التاريخ بصيغة YYYY-MM-DD.',

            'due_date.required'          => 'تاريخ الاستحقاق مطلوب.',
            'due_date.after_or_equal'    => 'تاريخ الاستحقاق لا يمكن أن يكون قبل تاريخ الفاتورة.',

            'product_id.required'        => 'يرجى اختيار المنتج.',
            'product_id.exists'          => 'المنتج المختار غير موجود في سجلاتنا.',

            'section_id.required'        => 'يرجى اختيار القسم.',
            'section_id.exists'          => 'القسم المختار غير موجود.',

            'amount_collection.numeric'  => 'مبلغ التحصيل يجب أن يكون رقماً.',
            'amount_commission.numeric'  => 'مبلغ العمولة يجب أن يكون رقماً.',
            'discount.numeric'           => 'مبلغ الخصم يجب أن يكون رقماً.',
            'total.numeric'              => 'الإجمالي يجب أن يكون رقماً.',

            'note.max'                   => 'الملاحظات يجب ألا تتجاوز 1000 حرف.',

        ];
    }
}
