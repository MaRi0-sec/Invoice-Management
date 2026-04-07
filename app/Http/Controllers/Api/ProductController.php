<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {
        $sections = Section::all();
        $products = Product::all();
        $data     = [$sections, $products];
        return response()->json(['data' => $data], 200);
    }

    public function store(Request $request)
    {

        $data = $request->validate(
            [
                'product_name'  => 'required | max:255',
                'section_id'    => 'required | exists:sections,id',
                'description'   => 'max:450',
            ],
            [
                'product_name.required' => 'من فضلك ادخل اسم المنتج',
                'section_id.required'   => 'من فضلك ادخل اسم القسم',
                'section_id.exists'     => 'اسم القسم غير موجود',
                'description.max'       => 'لقد تجاوزت الحد الاقصي من الوصف',

            ]
        );

        Product::create($data);

        return response()->json([
            'status'  => true,
            'message' => 'تم إضافة المنتج بنجاح',
        ], 201);
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

        $products = Product::where('id', $id)->first();
        if (!$products) {
            return response()->json([
                'status'  => false,
                'message' => 'This Product Is Not Found',
            ], 404);
        }

        $products->update($data);

        return response()->json([
            'status'  => true,
            'message' => 'تم تعديل المنتج بنجاح',
        ], 201);
    }

    public function destroy($id)
    {
        $products = Product::where('id', $id)->first();
        if (!$products) {
            return response()->json([
                'status'  => false,
                'message' => 'This Product Is Not Found',
            ], 404);
        }
        if ($products->invoices()->exists()) {
            return response()->json([
                'status'  => false,
                'message' => 'you can\'t delete this product because it ....',
            ], 422);
        }

        $products->forceDelete();

        return response()->json([
            'status'  => true,
            'message' => 'Done The Delete',
        ], 201);
    }
}
