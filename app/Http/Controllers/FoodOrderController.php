<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FoodOrder;
use App\Models\FoodDrink;
class FoodOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            //with is joint "jointure" between client and fooddrinks
            //client is the function defined in foodOrder model that defines relationships
            //food is the function defined in foodOrder model that defines relationships
            $orders = FoodOrder::with(['client', 'food'])->get();

            return response()->json([
                'success' => true,
                'data' => $orders,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch orders.',
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
            // Validate the incoming request
            $validatedData = $request->validate([
                'client_id' => 'required|exists:clients,id',
                'food_id' => 'required|exists:food_drinks,id',
                'quantity' => 'required|numeric|min:1',  // Add quantity validation if applicable
            ]);

            // Retrieve the unit price of the selected food item
            $food = FoodDrink::findOrFail($validatedData['food_id']); // Fetch food item by ID
            $unitPrice = $food->unit_price; // Get the unit price

            // Calculate total price (unit price * quantity)
            $totalPrice = $unitPrice * $validatedData['quantity'];  // Assume 'quantity' is provided

            // Add the total price to the validated data
            $validatedData['total_price'] = $totalPrice;

            // Create the new food order
            $order = FoodOrder::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Food order created successfully.',
                'order' => $order,
            ]);
        } catch (\Exception $e) {
            // Handle any errors
            return response()->json([
                'success' => false,
                'message' => 'Failed to create food order.',
                'error' => $e->getMessage(),
            ], 500);
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
        try {
            $validatedData = $request->validate([
                'client_id' => 'sometimes|exists:clients,id',
                'food_id' => 'sometimes|exists:food_drinks,id',
                'total_price' => 'sometimes|numeric|min:0',
                'order_date' => 'sometimes|date',
            ]);

            $order = FoodOrder::findOrFail($id);
            $order->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully.',
                'data' => $order,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order.',
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
            $order = FoodOrder::findOrFail($id);
            $order->delete();

            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully.',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete order.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
