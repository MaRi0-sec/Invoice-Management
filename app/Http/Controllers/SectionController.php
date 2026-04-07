<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{



    public function __construct()
    {
        // عرض الأقسام
        $this->middleware('permission:الاقسام')->only([
            'index'
        ]);

        // إضافة قسم
        $this->middleware('permission:اضافة قسم')->only([
            'store'
        ]);

        // تعديل قسم
        $this->middleware('permission:تعديل قسم')->only([
            'update'
        ]);

        // حذف قسم
        $this->middleware('permission:حذف قسم')->only([
            'destroy'
        ]);
    }


    public function index()
    {
        $sections = Section::all();
        return view('pages.sections.sections', compact('sections'));
    }

    public function store(Request $request)
    {

        $data = $request->validate(
            [
                'section_name' => 'required|unique:sections|max:255',
            ],
            [
                'section_name.required' => 'يرجي ادخال اسم القسم',
                'section_name.unique' => 'اسم القسم مسجل مسبقا',
            ]
        );

        Section::create(array_merge($data, [
            'description'  => $request->description,
            'created_by'   => Auth::user()->name,
            'user_id'   => Auth::id(),
        ]));

        return redirect('/section')->with('Add', 'تم اضافة القسم بنجاح ');
    }


    public function update(Request $request)
    {

        $validation = $request->validate(
            [
                'section_name' => 'required|max:255|unique:sections,section_name,' . $request->id,
            ],
            [
                'section_name.required' => 'يرجي ادخال اسم القسم',
                'section_name.unique' => 'اسم القسم مسجل مسبقا',
            ]
        );

        $section = Section::findOrFail($request->id);

        $section->update(array_merge($validation, [
            'description'  => $request->description,
            'created_by'   => Auth::user()->name,
        ]));

        return redirect('/section')->with('edit', 'تم تعديل القسم بنجاج');
    }

    public function destroy(Request $request)
    {

        $validation = $request->validate(
            [
                'id' => 'required|exists:sections,id',
            ],
            [
                'exists' => 'القسم غير موجود',
            ]
        );

        $section = Section::findOrFail($validation['id']);

        if ($section->invoices()->exists()) {
            return redirect('/section')->with('delete', 'لا يمكن حذف القسم لانه موجود في فاتورة');
        }

        $section->forceDelete();

        return redirect('/section')->with('delete', 'تم حذف القسم بنجاح');
    }
}
