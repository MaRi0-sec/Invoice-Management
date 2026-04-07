<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUsersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [

            "name" => "required|string|max:50",

            "email" =>
            [
                'required',
                'email',
                Rule::unique('users')->ignore($this->route('User'))      // عشان يتجاهل ايميل المستخدم دا اثناء البحث في الداتا بيز
            ],

            "password" => "nullable|min:8|same:confirm-password",

            "confirm-password" => "nullable|min:8",

            "status" => "required|in:0,1",

            "roles_name" => "required|array",
            "roles_name.*" => "exists:roles,name",

        ];
    }

    public function messages(): array
    {
        return [

            "name.required" => "اسم المستخدم مطلوب",
            "name.max" => "اسم المستخدم يجب ألا يزيد عن 50 حرف",

            "email.required" => "البريد الإلكتروني مطلوب",
            "email.email" => "صيغة البريد الإلكتروني غير صحيحة",
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل',

            "password.min" => "كلمة المرور يجب أن تكون 8 أحرف على الأقل",
            "password.same" => "كلمتا المرور غير متطابقتين",

            "confirm-password.required" => "تأكيد كلمة المرور مطلوب",
            "confirm-password.min" => "تأكيد كلمة المرور يجب أن يكون 8 أحرف على الأقل",

            "status.required" => "حالة المستخدم مطلوبة",
            "status.in" => "قيمة الحالة غير صحيحة",

            "roles_name.required" => "يجب اختيار صلاحية واحدة على الأقل",
            "roles_name.array" => "الصلاحيات غير صحيحة",
            "roles_name.*.exists" => "إحدى الصلاحيات المختارة غير موجودة",

        ];
    }
}
