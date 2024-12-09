<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FoodDrink;
class FoodDrinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $drinks = FoodDrink::all();

            return response()->json([
                'success' => true,
                'data' => $drinks,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve drinks.',
                'error' => $e->getMessage(),
            ], 500);
        }
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
            // Validate the request data
            $validatedData = $request->validate([
                'description' => 'required|string|max:255',
                'unit_price' => 'required|numeric|min:0',
            ]);

            // Create a new FoodDrink record
            $foodDrink = FoodDrink::create($validatedData);

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Food/Drink item created successfully.',
                'data' => $foodDrink,
            ], 201);
        } catch (\Exception $e) {
            // Handle exceptions and return error response
            return response()->json([
                'success' => false,
                'message' => 'Failed to create Food/Drink item.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $name)
    {
        try {
            $drink = FoodDrink::where('description', 'like', "%$name%")->get();

            if ($drink->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No drink found with the given name.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $drink,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve drink.',
                'error' => $e->getMessage(),
            ], 500);
        }
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
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'description' => 'sometimes|required|string|max:255',
                'unit_price' => 'sometimes|required|numeric|min:0',
            ]);

            // Find the drink by ID
            $drink = FoodDrink::findOrFail($id);

            // Update the drink
            $drink->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Drink updated successfully.',
                'data' => $drink,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Drink not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update the drink.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Find the drink by ID
            $drink = FoodDrink::findOrFail($id);

            // Delete the drink
            $drink->delete();

            return response()->json([
                'success' => true,
                'message' => 'Drink deleted successfully.',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Drink not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete the drink.',
                'error' => $e->getMessage(),
            ], 500);
        }

    }
}
