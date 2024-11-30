<?php

namespace App\Http\Controllers;

use App\Models\Provider2;
use Illuminate\Http\Request;

class Provider2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all providers managed by the authenticated admin
        $providers= Provider2::all(); // Fetch all waiters
        return response()->json([
            'success' => true,
            'data' => $providers
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

        // Validate incoming request data

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:providers,email',
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'zip' => 'required|string|max:20',
                'country' => 'required|string|max:255',
                'password' => 'required|string|min:8|confirmed',
                'admin_id' => 'required|exists:admins,id',
                // Assuming password confirmation is sent
            ]);

            // Create a new provider
            $provider = Provider2::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'zip' => $validated['zip'],
                'country' => $validated['country'],
                'password' => bcrypt($validated['password'],), // Hash the password
                //'admin_id' => $validated['admin_id'],
                'admin_id' => auth()->id(), // Associate with the authenticated admin
            ]);

            // Return a JSON response with the created provider data
            return response()->json([
                'success' => true,
                'data' => $provider
            ], 201);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Provider2 $provider2)
    {
        try {
            // Return the waiter data as JSON if found
            return response()->json($provider2);

        } catch (ModelNotFoundException $e) {
            // Handle the case where the waiter is not found
            return response()->json(['error' => 'Waiter not found'], Response::HTTP_NOT_FOUND);

        } catch (\Exception $e) {
            // Catch any other general exceptions
            return response()->json(['error' => 'An error occurred while fetching the waiter'], Response::HTTP_INTERNAL_SERVER_ERROR);
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
            $validated = $request->validate([
                'first_name' => 'sometimes|string|max:255',
                'last_name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:providers,email,' . $id,
                'phone' => 'sometimes|string|max:20',
                'address' => 'sometimes|string|max:255',
                'city' => 'sometimes|string|max:255',
                'state' => 'sometimes|string|max:255',
                'zip' => 'sometimes|string|max:20',
                'country' => 'sometimes|string|max:255',
                'password' => 'sometimes|string|min:8|confirmed', // Assuming password confirmation is sent
            ]);

            // Find the provider by ID or throw an exception if not found
            $provider = Provider2::find($id);

            if (!$provider) {
                return response()->json([
                    'success' => false,
                    'message' => 'Provider not found'
                ], 404);
            }

            // If password is present, hash it
            if ($request->has('password')) {
                $validated['password'] = bcrypt($validated['password']);
            }

            // Update the provider with the validated data
            $provider->update($validated);

            // Return a JSON response with the updated provider data
            return response()->json([
                'success' => true,
                'data' => $provider
            ]);
        } catch (\Exception $e) {
            // Catch any exceptions and return an error message
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Provider2 $provider2)
    {
        try {
            // Attempt to delete the waiter record
            $provider2->delete();

            // Return a success response if deletion is successful
            return response()->json(null, Response::HTTP_NO_CONTENT);

        } catch (ModelNotFoundException $e) {
            // Handle the case where the model is not found
            return response()->json(['error' => 'Waiter not found'], Response::HTTP_NOT_FOUND);

        } catch (QueryException $e) {
            // Handle any issues with the database query (e.g., foreign key constraint violation)
            return response()->json(['error' => 'Database error'], Response::HTTP_INTERNAL_SERVER_ERROR);

        } catch (\Exception $e) {
            // Catch any other general exceptions
            return response()->json(['error' => 'An error occurred while deleting the waiter'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
