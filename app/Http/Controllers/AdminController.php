<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = Admin::all();
        return response()->json($admins);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // This is not typically needed for API, as creation is handled via POST requests
        return response()->json(['message' => 'Show create form (not needed in API)']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email'=>'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $admin = Admin::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);
        /*
        DB::table('admins')->insert([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);*/
        $admin = DB::table('admins')->where('email', $validated['email'])->first();
        return response()->json($admin, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $admin = Admin::findOrFail($id);
        return response()->json($admin);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // This is not typically needed for API, as update is handled via PUT/PATCH requests
        return response()->json(['message' => 'Show edit form (not needed in API)']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:admins,email,' . $id,
            'password' => 'sometimes|required|string|min:8|confirmed',
        ]);

        $admin = Admin::findOrFail($id);
        $admin->update([
            'name' => $validated['name'] ?? $admin->name,
            'email' => $validated['email'] ?? $admin->email,
            'password' => isset($validated['password']) ? bcrypt($validated['password']) : $admin->password,
        ]);

        return response()->json($admin);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();

        return response()->json(['message' => 'Admin deleted successfully'], Response::HTTP_OK);
    }

    public function getAllOrders(Request $request)
    {
        // Optionally filter or paginate the orders
        $orders = Order::with(['admin', 'product'])->get();

        return response()->json([
            'success' => true,
            'orders' => $orders,
        ]);
    }

    public function placeOrder(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'provider_id' => 'required|exists:providers,id',
                'admin_id' => 'nullable|exists:admins,id',
                'product_id' => 'required|exists:products,id',  // Ensure this references the correct table
                'quantity' => 'required|integer|min:1',  // Add validation for quantity
            ]);

            // Calculate the total price (price * quantity)
            $validated['total_price'] = $validated['price'] * $validated['quantity'];

            // Set the status field to 'pending' if it is not provided in the request
            $validated['status'] = $validated['status'] ?? 'pending';

            // Temporarily using validated data for admin_id until authentication is set up
            $validated['admin_id'] = $validated['admin_id'] ?? 1;  // Replace '1' with a valid admin ID if needed

            // Create the order with the validated data
            $order = Order::create($validated);

            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Order created successfully.',
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error creating order: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error creating order: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function cancelOrder($orderId)
    {
        try {
            // Find the order by its ID
            $order = Order::findOrFail($orderId);

            // Check if the order status is not already canceled
            if ($order->status === 'cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Order has already been canceled.',
                ], 400);
            }

            // Update the order status to 'canceled'
            $order->status = 'cancelled';
            $order->save();

            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Order has been canceled successfully.',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Error canceling order: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error canceling order: ' . $e->getMessage(),
            ], 500);
        }
    }
}
