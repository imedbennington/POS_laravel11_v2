<?php

namespace App\Http\Controllers;

use App\Models\Waiter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
class WaiterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $waiters = Waiter::all(); // Fetch all waiters
        return response()->json($waiters);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:waiters',
                'password' => 'required|string|min:8|confirmed',
                'basic_salary' => 'required|numeric',
                'days_off' => 'required|integer',
                'admin_id' => 'required|exists:admins,id',
            ]);

            $waiter = Waiter::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'basic_salary' => $validated['basic_salary'],
                'days_off' => $validated['days_off'],
                'admin_id' => $validated['admin_id'],
            ]);
            $waiter->assignRole('waiter');
            return response()->json([
                'success' => true,
                'data' => $waiter
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Waiter $waiter)
    {
        try {
            // Return the waiter data as JSON if found
            return response()->json($waiter);

        } catch (ModelNotFoundException $e) {
            // Handle the case where the waiter is not found
            return response()->json(['error' => 'Waiter not found'], Response::HTTP_NOT_FOUND);

        } catch (\Exception $e) {
            // Catch any other general exceptions
            return response()->json(['error' => 'An error occurred while fetching the waiter'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Waiter $waiter)
    {
        try {
            // Validate the incoming request data
            $validated = $request->validate([
                'first_name' => 'nullable|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'email' => 'nullable|string|email|max:255|unique:waiters,email,' . $waiter->id,
                'password' => 'nullable|string|min:8|confirmed',
                'basic_salary' => 'nullable|numeric',
                'days_off' => 'nullable|integer',
                'admin_id' => 'nullable|exists:admins,id',
            ]);

            // Update the waiter instance with validated data
            if ($validated['first_name']) {
                $waiter->first_name = $validated['first_name'];
            }
            if ($validated['last_name']) {
                $waiter->last_name = $validated['last_name'];
            }
            if ($validated['email']) {
                $waiter->email = $validated['email'];
            }
            if ($validated['password']) {
                $waiter->password = bcrypt($validated['password']);
            }
            if ($validated['basic_salary']) {
                $waiter->basic_salary = $validated['basic_salary'];
            }
            if ($validated['days_off']) {
                $waiter->days_off = $validated['days_off'];
            }
            if ($validated['admin_id']) {
                $waiter->admin_id = $validated['admin_id'];
            }

            // Save the updated waiter instance
            $waiter->save();

            return response()->json([
                'success' => true,
                'data' => $waiter
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Waiter $waiter)
    {
        try {
            // Attempt to delete the waiter record
            $waiter->delete();

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
