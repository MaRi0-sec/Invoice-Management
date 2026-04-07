<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function __construct()
    {
        // عرض المنتجات
        $this->middleware('permission:المنتجات')->only([
            'index'
        ]);

        // إضافة منتج
        $this->middleware('permission:اضافة منتج')->only([
            'store'
        ]);

        // تعديل منتج
        $this->middleware('permission:تعديل منتج')->only([
            'update'
        ]);

        // حذف منتج
        $this->middleware('permission:حذف منتج')->only([
            'destroy'
        ]);
    }

    public function index()
    {
        $sections = Section::all();
        $products = Product::all();
        return view('pages.products.products', compact('sections', 'products'));
    }

    public function store(Request $request)
    {

        $data = $request->validate(
            [
                'product_name' => 'required | max:255',
                'section_id'   => 'required | exists:sections,id',
                'description'   => 'max:450',
            ],
            [
                'product_name.required' => 'من فضلك ادخل اسم المنتج',
                'section_id.required'   => 'من فضلك ادخل اسم القسم',
                'section_id.exists'   => 'اسم القسم غير موجود',
                'description.max'   => 'لقد تجاوزت الحد الاقصي من الوصف',

            ]
        );

        Product::create($data);
        session()->flash('Add', 'تم اضافة المنتج بنجاح ');
        return redirect('/product');
    }

    public function update($id, Request $request)
    {
        $data = $request->validate(
            [
                'product_name' => 'required|max:255',
                'section_id'   => 'required|exists:sections,id',
                'description'   => 'max:450',
            ],
            [
                'product_name.required' => 'من فضلك ادخل اسم المنتج',
                'section_id.required'   => 'من فضلك ادخل اسم القسم',
                'section_id.exists'   => 'اسم القسم غير موجود',
                'description.max'   => 'لقد تجاوزت الحد الاقصي من الوصف',
            ]
        );

        $products = Product::findOrFail($id);

        $products->update($data);

        session()->flash('Edit', 'تم تعديل المنتج بنجاح');
        return back();
    }

    public function destroy($id)
    {
        $products = Product::findOrFail($id);
        if ($products->invoices()->exists()) {
            return back()->with('delete', 'لا يمكنك حذف المنتج لانه مستخدم في فاتوره');
        }

        $products->forceDelete();

        return back()->with('delete', 'تم حذف المنتج بنجاح');
    }
}
