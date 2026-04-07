@extends('layouts.master')

@section('css')
    <link href="{{ URL::asset('assets/plugins/prism/prism.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/inputtags/inputtags.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/custom-scroll/jquery.mCustomScrollbar.css') }}" rel="stylesheet">
@endsection

@section('title')
    تفاصيل فاتورة
@stop

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">قائمة الفواتير</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تفاصيل الفاتورة</span>
            </div>
        </div>
    </div>
    @endsection

@section('content')

    {{-- الرسائل التحذيرية والنجاح --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session()->has('Add'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('Add') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session()->has('delete'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('delete') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session()->has('error_if_exests'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('error_if_exests') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card mg-b-20" id="tabs-style2">
                <div class="card-body">
                    <div class="text-wrap">
                        <div class="example">
                            <div class="panel panel-primary tabs-style-2">
                                <div class=" tab-menu-heading">
                                    <div class="tabs-menu1">
                                        <ul class="nav panel-tabs main-nav-line">
                                            <li><a href="#tab4" class="nav-link active" data-toggle="tab">معلومات الفاتورة</a></li>
                                            <li><a href="#tab5" class="nav-link" data-toggle="tab">حالات الدفع</a></li>
                                            <li><a href="#tab6" class="nav-link" data-toggle="tab">المرفقات</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="panel-body tabs-menu-body main-content-body-right border">
                                    <div class="tab-content">

                                        {{-- التبويب الأول: معلومات الفاتورة --}}
                                        <div class="tab-pane active" id="tab4">
                                            <div class="table-responsive mt-15">
                                                <table class="table table-striped" style="text-align:center">
                                                    <tbody>
                                                        <tr>
                                                            <th scope="row">رقم الفاتورة</th>
                                                            <td>{{ $invoice->invoice_number }}</td>
                                                            <th scope="row">تاريخ الاصدار</th>
                                                            <td>{{ $invoice->invoice_date }}</td>
                                                            <th scope="row">تاريخ الاستحقاق</th>
                                                            <td>{{ $invoice->due_date }}</td>
                                                            <th scope="row">القسم</th>
                                                            <td>{{ $invoice->section->section_name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">المنتج</th>
                                                            <td>{{ $invoice->product->product_name }}</td>
                                                            <th scope="row">مبلغ التحصيل</th>
                                                            <td>{{ number_format($invoice->amount_collection, 2) }}</td>
                                                            <th scope="row">مبلغ العمولة</th>
                                                            <td>{{ number_format($invoice->amount_commission, 2) }}</td>
                                                            <th scope="row">الخصم</th>
                                                            <td>{{ number_format($invoice->discount, 2) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">نسبة الضريبة</th>
                                                            <td>{{ $invoice->rate_vat }}</td>
                                                            <th scope="row">قيمة الضريبة</th>
                                                            <td>{{ number_format($invoice->value_vat, 2) }}</td>
                                                            <th scope="row">الاجمالي مع الضريبة</th>
                                                            <td>{{ number_format($invoice->total_with_value_vat, 2) }}</td>
                                                            <th scope="row">الحالة الحالية</th>
                                                            <td>
                                                                @if ($invoice->value_status == 1)
                                                                    <span class="badge badge-pill badge-success">{{ $invoice->status }}</span>
                                                                @elseif($invoice->value_status == 2)
                                                                    <span class="badge badge-pill badge-danger">{{ $invoice->status }}</span>
                                                                @else
                                                                    <span class="badge badge-pill badge-warning">{{ $invoice->status }}</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">ملاحظات</th>
                                                            <td colspan="7">{{ $invoice->note }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        {{-- التبويب الثاني: حالات الدفع --}}
                                        <div class="tab-pane" id="tab5">
                                            <div class="table-responsive mt-15">
                                                <table class="table center-aligned-table mb-0 table-hover" style="text-align:center">
                                                    <thead>
                                                        <tr class="text-dark">
                                                            <th>#</th>
                                                            <th>رقم الفاتورة</th>
                                                            <th>تاريخ الاضافة </th>
                                                            <th>نوع المنتج</th>
                                                            <th>القسم</th>
                                                            <th>حالة الدفع</th>
                                                            <th>المبلغ المدفوع</th>
                                                            <th>الاجمالي</th>
                                                            <th>المبلغ المتبقي</th>
                                                            <th>تاريخ الدفع </th>
                                                            <th>ملاحظات</th>
                                                            <th>المستخدم</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($details as $index => $detail)
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
                                                                <td>{{ $detail->invoice_number }}</td>
                                                                <td>{{ $detail->created_at->format('Y-m-d') }}</td>

                                                                <td>{{ $detail->product }}</td>
                                                                <td>{{ $invoice->section->section_name }}</td>
                                                                <td>
                                                                    @if ($detail->value_status == 1)
                                                                        <span class="badge badge-pill badge-success">{{ $detail->status }}</span>
                                                                    @elseif($detail->value_status == 2)
                                                                        <span class="badge badge-pill badge-danger">{{ $detail->status }}</span>
                                                                    @elseif($detail->value_status == 3)
                                                                        <span class="badge badge-pill badge-warning">{{ $detail->status }}</span>
                                                                    @endif
                                                                </td>
                                                                <td>{{ $detail->amount_paid }}</td>
                                                                <td>{{ $detail->total_with_value_vat }}</td>
                                                                <td>{{ $detail->remaining_amount }}</td>
                                                                <td>{{ $detail->payment_date }}</td>
                                                                <td>{{ $detail->note }}</td>
                                                                <td>{{ $detail->user }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        {{-- التبويب الثالث: المرفقات --}}
<div class="tab-pane" id="tab6">
    <div class="card card-statistics">

        @can('اضافة مرفق')
        @if(!$invoice->deleted_at)
        <div class="card-body">
            <p class="text-danger">* صيغة المرفق pdf, jpeg ,.jpg , png </p>
            <h5 class="card-title">اضافة مرفقات</h5>

            <form method="post" action="{{ route('invoiceAttachment.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="customFile" name="attachment" required>

                    <input type="hidden" name="invoice_number" value="{{ $invoice->invoice_number }}">
                    <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">

                    <label class="custom-file-label" for="customFile">حدد المرفق</label>
                </div>

                <br><br>

                <button type="submit" class="btn btn-primary btn-sm">تاكيد</button>
            </form>
        </div>
        @endif
        @endcan


        <div class="table-responsive mt-15">
            <table class="table center-aligned-table mb-0 table-hover" style="text-align:center">

                <thead>
                    <tr class="text-dark">
                        <th>#</th>
                        <th>اسم الملف</th>
                        <th>قام بالاضافة</th>
                        <th>تاريخ الاضافة</th>
                        <th>العمليات</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($attachments as $index => $attachment)

                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $attachment->file_name }}</td>
                        <td>{{ $attachment->created_by }}</td>
                        <td>{{ $attachment->created_at->format('Y-m-d') }}</td>

                        <td>

                            <a class="btn btn-outline-success btn-sm"
                                href="{{ asset('Attachments/'.$invoice->invoice_number.'/'.$attachment->file_name) }}"
                                role="button">
                                <i class="fas fa-eye"></i> عرض
                            </a>


                            <a class="btn btn-outline-info btn-sm"
                                href="{{ route('download_file' , [$invoice->invoice_number , $attachment->file_name]) }}"
                                role="button">
                                <i class="fas fa-download"></i> تحميل
                            </a>


                            @can('حذف المرفق')
                                <a class="modal-effect btn btn-sm btn-danger"
                                href="#modaldemo9"
                                data-effect="effect-scale"
                                data-toggle="modal"
                                data-file_name="{{ $attachment->file_name }}"
                                data-invoice_number="{{ $attachment->invoice_number }}"
                                data-file_id="{{ $attachment->id }}">
                                حذف
                            </a>
                            @endcan

                        </td>

                    </tr>

                    @endforeach
                </tbody>

            </table>
        </div>

    </div>
</div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FORM DELETE --}}



    <div class="modal" id="modaldemo9">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">حذف القسم</h6><button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="deleteForm" method="post">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <h6 style="color:red"> هل انت متاكد من عملية حذف المرفق ؟</h6>
                        <input type="hidden" name="file_id" id="file_id" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                        <button type="submit" class="btn btn-danger">تاكيد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- End FORM DELETE --}}


@endsection

@section('js')

    <script>

    $('#modaldemo9').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var file_id = button.data('file_id')
        var file_name = button.data('file_name')
        var invoice_number = button.data('invoice_number')
        var modal = $(this)
            modal.find('.modal-body #file_name').val(file_name);
            modal.find('.modal-body #invoice_number').val(invoice_number);
            modal.find('.modal-body #file_id').val(file_id);
            modal.find('#deleteForm').attr('action', '/invoiceAttachment/' + file_id)
    })
    
    </script>

    <script>

        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

    </script>
@endsection

