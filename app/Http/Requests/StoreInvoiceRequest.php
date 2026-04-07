<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
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
            'invoice_number'              => 'required|unique:invoices,invoice_number|max:50',
            'invoice_date'                => 'required|date|date_format:Y-m-d',
            'due_date'                    => 'required|date|date_format:Y-m-d|after_or_equal:invoice_date',
            'product_id'                  => 'required|exists:products,id',
            'section_id'                  => 'required|exists:sections,id',
            'amount_collection'           => 'required|numeric|min:0|gte:amount_commission',
            'amount_commission'           => 'required|numeric|min:0|gte:discount',
            'discount'                    => 'required|numeric|min:0|lte:amount_commission',            // lte (Less Than or Equal)أقل من أو يساوي
            'value_vat'                   => 'required|numeric',
            'rate_vat'                    => 'required|string|max:10',
            'total_with_value_vat'        => 'nullable|numeric',
            'total'                       => 'nullable|numeric',
            'note'                        => 'nullable|string|max: 1000',
            'attachment'                  => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
        ];
    }
    public function messages(): array
    {
        return [
            'invoice_number.required'       => 'يرجى إدخال رقم الفاتورة.',
            'invoice_number.unique'         => 'رقم الفاتورة هذا مسجل مسبقاً، يرجى التأكد من الرقم.',
            'invoice_number.max'            => 'رقم الفاتورة طويل جداً، الحد الأقصى 50 حرفاً.',

            'invoice_date.required'         => 'تاريخ الفاتورة مطلوب.',
            'invoice_date.date'             => 'صيغة التاريخ غير صحيحة.',
            'invoice_date.date_format'      => 'يجب أن يكون التاريخ بصيغة YYYY-MM-DD.',

            'due_date.required'             => 'تاريخ الاستحقاق مطلوب.',
            'due_date.after_or_equal'       => 'تاريخ الاستحقاق لا يمكن أن يكون قبل تاريخ الفاتورة.',

            'product_id.required'           => 'يرجى اختيار المنتج.',
            'product_id.exists'             => 'المنتج المختار غير موجود في سجلاتنا.',

            'section_id.required'           => 'يرجى اختيار القسم.',
            'section_id.exists'             => 'القسم المختار غير موجود.',

            'amount_collection.numeric'     => 'مبلغ التحصيل يجب أن يكون رقماً.',
            'amount_collection.gte'         => 'مبلغ التحصيل يجب أن يكون أكبر من أو يساوي مبلغ العمولة',

            'amount_commission.numeric'     => 'مبلغ العمولة يجب أن يكون رقماً.',

            'discount.numeric'              => 'مبلغ الخصم يجب أن يكون رقماً.',
            'discount.lte'                  => 'مبلغ الخصم لا يمكن أن يتجاوز مبلغ العمولة الأصلي.',

            'total.numeric'                 => 'الإجمالي يجب أن يكون رقماً.',

            'total_with_value_vat.numeric'  => 'الإجمالي يجب أن يكون رقماً.',

            'note.max'                      => 'الملاحظات يجب ألا تتجاوز 1000 حرف.',

            'attachment.mimes'              => 'صيغة المرفق يجب أن تكون pdf, jpg, png, jpeg فقط.',
            'attachment.max'                => 'حجم المرفق كبير جداً، الحد الأقصى هو 2 ميجابايت.',
            'attachment.file'               => 'يجب أن يكون المرفق ملفاً صحيحاً.',
        ];
    }
}



// . نصيحة إضافية (التحقق من "نسبة" الخصم)
// في المشاريع الكبيرة، أحياناً لا يُسمح للموظف بعمل خصم يتجاوز نسبة معينة (مثلاً 50% من العمولة) إلا بإذن مدير. إذا أردت فعل ذلك، نستخدم Closure (دالة مخصصة) داخل الـ Validation: اشرح دا




// <?php

// namespace App\Http\Requests;

// use Illuminate\Foundation\Http\FormRequest;

// class StoreInvoiceRequest extends FormRequest
// {
//     public function authorize(): bool
//     {
//         return true; // مسموح للكل حالياً
//     }

//     public function rules(): array
//     {
//         return [
//             'invoice_number'        => 'required|unique:invoices,invoice_number|max:50',
//             'invoice_date'          => 'required|date|date_format:Y-m-d',
//             'due_date'              => 'required|date|date_format:Y-m-d|after_or_equal:invoice_date',
//             'product_id'            => 'required|exists:products,id',
//             'section_id'            => 'required|exists:sections,id',
//             'amount_collection'     => 'required|numeric|min:0|gte:amount_commission',
//             'amount_commission'     => 'required|numeric|min:0',
//             'discount'              => [
//                 'required',
//                 'numeric',
//                 'min:0',
//                 'lte:amount_commission',
//                 // الـ Closure للتحقق من أن الخصم لا يتجاوز 50% من العمولة
//                 function ($attribute, $value, $fail) {
//                     $commission = (float) $this->amount_commission;
//                     $maxAllowed = $commission * 0.50;
//                     if ($value > $maxAllowed) {
//                         $fail("عفواً! الخصم ($value) لا يمكن أن يتجاوز نصف العمولة ($maxAllowed).");
//                     }
//                 },
//             ],
//             'rate_vat'              => 'required|string|max:10',
//             'note'                  => 'nullable|string|max:1000',
//             'attachment'            => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
//         ];
//     }

//     public function messages(): array
//     {
//         return [
//             'invoice_number.required'    => 'يرجى إدخال رقم الفاتورة.',
//             'invoice_number.unique'      => 'رقم الفاتورة مسجل مسبقاً، يرجى التأكد من الرقم.',
//             'due_date.after_or_equal'    => 'تاريخ الاستحقاق لا يمكن أن يكون قبل تاريخ الفاتورة.',
//             'product_id.required'        => 'يرجى اختيار المنتج.',
//             'section_id.required'        => 'يرجى اختيار القسم.',
//             'amount_collection.gte'      => 'مبلغ التحصيل يجب أن يكون أكبر من أو يساوي مبلغ العمولة.',
//             'amount_commission.required' => 'يرجى إدخال مبلغ العمولة.',
//             'discount.lte'               => 'مبلغ الخصم لا يمكن أن يتجاوز مبلغ العمولة الأصلي.',
//             'attachment.mimes'           => 'صيغة المرفق يجب أن تكون pdf, jpg, png, jpeg فقط.',
//             'attachment.max'             => 'حجم المرفق لا يجب أن يتخطى 2 ميجا.',
//         ];
//     }
// }

// هل تريد مني شرح كيفية عمل "تنبيه" (Notification) يرسل للمدير تلقائياً عند إضافة فاتورة بخصم كبير؟