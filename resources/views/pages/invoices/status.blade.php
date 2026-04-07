@extends('layouts.master')
@section('css')
    <!--Internal   Notify -->
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection
@section('title')
    تغير حالة الدفع
@stop
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    تغير حالة الدفع</span>
            </div>
        </div>

    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->

    @if (session()->has('success'))
        <script>
            window.onload = function() {
                notif({
                    msg: "تم تحديث حالة الدفع بنجاح",
                    type: "success"
                })
            }
        </script>
    @endif

    <div class="row">



@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif




        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                        {{-- 1 --}}
                        <div class="row">
                            <div class="col">
                                <label for="invoice_number" class="control-label">رقم الفاتورة</label>
                                <input type="text" class="form-control" id="invoice_number"
                                    value="{{ $invoice->invoice_number }}" readonly>
                            </div>

                            <div for="invoice_date" class="col">
                                <label>تاريخ الفاتورة</label>
                                <input class="form-control fc-datepicker" id="invoice_date" placeholder="YYYY-MM-DD"
                                    type="text" value="{{ $invoice->invoice_date }}" readonly>
                            </div>

                            <div class="col" for="due_date">
                                <label>تاريخ الاستحقاق</label>
                                <input class="form-control fc-datepicker" id="due_date" placeholder="YYYY-MM-DD"
                                    type="text" value="{{ $invoice->due_date }}" readonly>
                            </div>

                        </div>

                        {{-- 2 --}}
                        <div class="row">
                            <div class="col">
                                <label for="section_name" class="control-label">القسم</label>
                                <select class="form-control" id="section_name" readonly>
                                    <option value="{{ $invoice->section->id }}">
                                        {{ $invoice->section->section_name }}
                                    </option>

                                </select>
                            </div>

                            <div class="col">
                                <label for="product" class="control-label">المنتج</label>
                                <select id="product" class="form-control" readonly>
                                    <option value="{{ $invoice->product->id }}"> {{ $invoice->product->product_name }}</option>
                                </select>
                            </div>

                            <div class="col">
                                <label for="amount_collection" class="control-label">مبلغ التحصيل</label>
                                <input type="text" class="form-control" id="amount_collection"
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                    value="{{ $invoice->amount_collection }}" readonly>
                            </div>
                        </div>


                        {{-- 3 --}}

                        <div class="row">

                            <div class="col">
                                <label for="amount_commission" class="control-label">مبلغ العمولة</label>
                                <input type="text" class="form-control form-control-lg" id="amount_commission"
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                    value="{{ $invoice->amount_commission }}" readonly>
                            </div>

                            <div class="col">
                                <label for="discount" class="control-label">الخصم</label>
                                <input type="text" class="form-control form-control-lg" id="discount"
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                    value="{{ $invoice->discount }}" readonly>
                            </div>

                            <div class="col">
                                <label for="rate_vat" class="control-label">نسبة ضريبة القيمة المضافة</label>
                                <select id="rate_vat" class="form-control" onchange="myFunction()" readonly>
                                    <!--placeholder-->
                                    <option value=" {{ $invoice->rate_vat }}">
                                        {{ $invoice->rate_vat }}
                                </select>
                            </div>

                        </div><br>

                        {{-- 4 --}}

                        <div class="row">
                            <div class="col">
                                <label for="value_vat" class="control-label">قيمة ضريبة القيمة المضافة</label>
                                <input type="text" class="form-control" id="value_vat"
                                    value="{{ $invoice->value_vat }}" readonly>
                            </div>

                            <div class="col">
                                <label for="total" class="control-label">الاجمالي</label>
                                <input type="text" class="form-control" id="total" readonly
                                    value="{{ $invoice->total }}">
                            </div>

                            <div class="col">
                                <label for="inputName" class="control-label">الاجمالي شامل الضريبة</label>
                                <input type="text" class="form-control" id="total_with_value_vat" readonly
                                    value="{{ $invoice->total_with_value_vat }}">
                            </div>

                            <div class="col">
                                <label for="remaining_amount" class="control-label">المتبقي</label>
                                <input type="text" class="form-control" id="remaining_amount" readonly
                                    value="{{ $invoice->remaining_amount  }}">
                            </div>
                        </div><br>
                    <form action="{{route('statusUpdate')}}" method="post" autocomplete="off">
                        @csrf()
                        @method('put')

                        <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                        <input type="hidden" name="invoice_number" value="{{ $invoice->invoice_number }}">

                        {{-- 5 --}}
                        <div class="row">
                            <div class="col">
                                <label for="exampleTextarea">ملاحظات</label>
                                <textarea class="form-control" id="exampleTextarea" name="note" rows="3">{{ $invoice->note }}</textarea>
                            </div>
                        </div><br>

                        <div class="row">
                            <div class="col">
                                <label for="status">حالة الدفع</label>
                                <select class="form-control" id="value_status" name="value_status" required onchange="showAmountPaid() , hiddenDate()">
                                    <option selected disabled>-- حدد حالة الدفع --</option>
                                    <option value="1">مدفوعة</option>
                                    <option value="2">غير مدفوعه</option>
                                    <option value="3">مدفوعة جزئيا</option>
                                </select>
                            </div>

                            <div class="col" id="amount_paid_field" style="display: none;">
                                <label>المبلغ المدفوع</label>
                                <input type="number" class="form-control"
                                name="amount_paid" id="amount_paid" step="0.01"
                                placeholder="0.00" onkeyup="calc()" onchange="calc()"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                            </div>

                            <div class="col" id="remaining_field" style="display: none;">
                                <label>الباقي</label>
                                <input type="text" class="form-control"
                                name="remaining_amount" id="remaining"
                                placeholder="0.00" readonly
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                            </div>

                            <div class="col" id="date_field" style="display: block;">
                                <label>تاريخ الدفع</label>
                                <input class="form-control fc-datepicker" name="payment_date" id="date" placeholder="YYYY-MM-DD" type="text" required>
                            </div>
                        </div><br>

                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">تحديث حالة الدفع</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection

@section('js')
    <!-- Internal Select2 js-->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!--Internal  Form-elements js-->
    <script src="{{ URL::asset('assets/js/advanced-form-elements.js') }}"></script>
    <script src="{{ URL::asset('assets/js/select2.js') }}"></script>
    <!--Internal Sumoselect js-->
    <script src="{{ URL::asset('assets/plugins/sumoselect/jquery.sumoselect.js') }}"></script>
    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!--Internal  jquery.maskedinput js -->
    <script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
    <!--Internal  spectrum-colorpicker js -->
    <script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
    <!-- Internal form-elements js -->
    <script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>
        <!--Internal  Notify js -->
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>

    <script>
        var date = $('.fc-datepicker').datepicker({
            dateFormat: 'yy-mm-dd'
        }).val();
    </script>

    <script>
        function hiddenDate() {
            var value_status = document.getElementById("value_status").value;
            var date = document.getElementById("date");

            if (value_status === "2") {
                date_field.style.display = "none";
                date.required = false;
            } else {
                date_field.style.display = "block";
                date.required = true;
                date_field.value = ""; // تفريغ الحقل إذا تم تغيير الحالة
            }
        }

        function showAmountPaid() {
            var value_status = document.getElementById("value_status").value;
            var amount_paid = document.getElementById("amount_paid");
            var amount_paid_field = document.getElementById("amount_paid_field");
            var remaining = document.getElementById("remaining");
            var remaining_field = document.getElementById("remaining_field");

            if (value_status === "3") {
                amount_paid_field.style.display = "block";
                amount_paid.required = true;
                remaining_field.style.display = "block";
                remaining.required = true;
            } else {
                amount_paid_field.style.display = "none";
                amount_paid.required = false;
                amount_paid_field.value = ""; // تفريغ الحقل إذا تم تغيير الحالة
                remaining_field.style.display = "none";
                remaining.required = false;
                remaining_field.value = ""; // تفريغ الحقل إذا تم تغيير الحالة
            }
        }

    var alertShown = false;

    function calc() {
        // الحصول على القيم
        var amount_paid = parseFloat(document.getElementById("amount_paid").value);
        var remaining_amount = parseFloat(document.getElementById("remaining_amount").value);
        
        // التحقق من صحة القيم
        if (isNaN(amount_paid)) {
            amount_paid = 0;
        }
        
        if (isNaN(remaining)) {
            remaining = 0;
        }
        
        // التحقق من إدخال المبلغ
        if (amount_paid === 0) {
            document.getElementById("remaining").value = "0";
            // إعادة تعيين المتغير لو المبلغ رجع 0
            alertShown = false;
        } else {
            // حساب المبلغ المتبقي
            var remaining = remaining_amount - amount_paid;
            
            // التأكد من أن المبلغ المتبقي ليس سالباً
            if (remaining < 0) {
                // ظهور alert مرة واحدة فقط
                if (!alertShown) {
                    alert('المبلغ المدفوع أكبر من إجمالي الفاتورة');
                    alertShown = true; // تعيين المتغير لمنع ظهور alert مرة أخرى
                }
                remaining = 0;
            } else {
                // إعادة تعيين المتغير لو المبلغ أصبح صحيحاً
                alertShown = false;
            }
            
            // تقريب الرقم لخانتين عشريتين
            var formatted_amount = remaining.toFixed(2);
            
            // عرض النتيجة
            document.getElementById("remaining").value = formatted_amount;
        }
    }
</script>



@endsection
