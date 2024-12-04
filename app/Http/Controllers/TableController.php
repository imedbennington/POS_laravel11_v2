<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Table;
class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate incoming JSON data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'number' => 'required|integer',
                'status' => 'nullable|in:free,reserved,cancelled',
                'seats' => 'required|integer',  // Validate seats as an integer
            ]);

            $status = $validated['status'] ?? 'free';

            // Create a new table record using validated data
            $table = new Table();
            $table->name = $validated['name'];
            $table->number = $validated['number'];
            $table->status = $status;
            $table->seats = $validated['seats'];

            // Debugging: Check the data before saving
            //dd($table);

            // Save the table
            $table->save();

            // Check if table is saved successfully
            if ($table->exists) {
                return response()->json([
                    'message' => 'Table created successfully',
                    'data' => $table,
                ], 201);  // HTTP Status Code 201 for resource creation
            } else {
                return response()->json([
                    'message' => 'Table creation failed',
                ], 500);  // HTTP Status Code 500 for general server errors
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return validation errors if any fields fail validation
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);  // HTTP Status Code 422 for validation errors

        } catch (\Exception $e) {
            // Catch any other unexpected errors
            return response()->json([
                'message' => 'An error occurred while creating the table',
                'error' => $e->getMessage(),
            ], 500);  // HTTP Status Code 500 for general server errors
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
