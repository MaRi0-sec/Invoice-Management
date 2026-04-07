<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class SectionController extends Controller
{
    /**
     * Display a listing of the sections.
     */
    public function index(): JsonResponse
    {
        $sections = Section::all();
        return response()->json([
            'status' => true,
            'data' => $sections
        ], 200);
    }

    /**
     * Store a newly created section.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'section_name' => 'required|unique:sections,section_name|max:255',
            'description'  => 'nullable|string',
        ], [
            'section_name.required' => 'Please enter the section name.',
            'section_name.unique'   => 'This section name is already registered.',
        ]);

        $section = Section::create([
            'section_name' => $data['section_name'],
            'description'  => $request->description,
            'created_by'   => Auth::user()->name,
            'user_id'      => Auth::id(),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Section added successfully',
            'data'    => $section
        ], 201);
    }

    /**
     * Update the specified section.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validation = $request->validate([
            'section_name' => 'required|max:255|unique:sections,section_name,' . $id,
            'description'  => 'nullable|string',
        ], [
            'section_name.required' => 'Please enter the section name.',
            'section_name.unique'   => 'This section name is already taken.',
        ]);

        $section = Section::find($id);

        if (!$section) {
            return response()->json([
                'status'  => false,
                'message' => 'Section not found'
            ], 404);
        }

        $section->update([
            'section_name' => $validation['section_name'],
            'description'  => $request->description,
            // Only update created_by if you want to track who last edited it
            'created_by'   => Auth::user()->name,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Section updated successfully',
            'data'    => $section
        ], 200);
    }

    /**
     * Remove the specified section.
     */
    public function destroy($id): JsonResponse
    {
        $section = Section::find($id);

        if (!$section) {
            return response()->json([
                'status'  => false,
                'message' => 'Section not found'
            ], 404);
        }

        // Logic check: prevent deletion if linked to invoices
        if ($section->invoices()->exists()) {
            return response()->json([
                'status'  => false,
                'message' => 'Cannot delete section because it is linked to existing invoices.'
            ], 422); // 422 Unprocessable Entity
        }

        $section->forceDelete();

        return response()->json([
            'status'  => true,
            'message' => 'Section deleted successfully'
        ], 200);
    }
}
