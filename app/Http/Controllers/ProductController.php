<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $providerId = $request->query('provider_id');
            $products = $providerId
                ? Product::where('provider_id', $providerId)->with('provider', 'admin')->get()
                : Product::with('provider', 'admin')->get();
            return response()->json([
                'success' => true,
                'data' => $products,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching products: ' . $e->getMessage(),
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
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'provider_id' => 'required|exists:providers,id',
                'admin_id' => 'nullable|exists:admins,id',
            ]);

            // Temporarily using validated data for admin_id until authentication is set up
            // $validated['admin_id'] = auth('admin')->id(); // Assign the current admin
            // For now, you can replace this line with the actual admin ID manually, like so:
             $validated['admin_id'] = 1;  // Replace '1' with a valid admin ID or handle later

            // Create the product with the validated data7
            //dd($validated);
            $product = Product::create($validated);

            return response()->json([
                'success' => true,
                'data' => $product,
                'message' => 'Product created successfully.',
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error creating product: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error creating product: ' . $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = Product::with('provider', 'admin')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $product,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching product: ' . $e->getMessage(),
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
            $product = Product::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'provider_id' => 'required|exists:providers,id',
            ]);

            $product->update($validated);

            return response()->json([
                'success' => true,
                'data' => $product,
                'message' => 'Product updated successfully.',
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating product: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully.',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting product: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function order(Request $request)
    {
        try {
            // Validate input data
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id', // Product must exist
                'quantity' => 'required|integer|min:1', // Quantity must be at least 1
                'provider_id' => 'required|exists:providers,id', // Provider must exist
                'admin_id' => 'nullable|exists:admins,id',
            ]);
            $validated['admin_id'] = 1;
            // Fetch the product based on the product_id
            $product = Product::where('id', $validated['product_id'])
                ->where('provider_id', $validated['provider_id']) // Ensure product belongs to the provider
                ->firstOrFail();

            // Calculate the total price
            $totalPrice = $product->price * $validated['quantity'];

            // Create the order
            ///$order = Order::create($validated);
            $order = Order::create([
                //'admin_id' => auth('admin')->id(), // Admin who is placing the order
                'provider_id'=>$validated['provider_id'],
                'admin_id'=>$validated['admin_id'],
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'total_price' => $totalPrice,
            ]);

            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Product ordered successfully.',
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error placing order: ' . $e->getMessage(),
            ], 500);
        }
    }
}
