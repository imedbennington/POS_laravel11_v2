<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\AdminNotification; // Model for storing notifications (optional)
use App\Events\InventoryIssueReported; // Event for notifying (optional)

class NotificationController extends Controller
{
    public function notifyAdmin(Request $request)
    {
        try {
            // Validate the incoming request
            $validatedData = $request->validate([
                'waiter_id' => 'required|integer',
                'issue' => 'required|string|max:255',
                'details' => 'nullable|string',
            ]);

            // Example of saving the notification to the database (optional)
            $notification = AdminNotification::create([
                'waiter_id' => $validatedData['waiter_id'],
                'issue' => $validatedData['issue'],
                'details' => $validatedData['details'],
            ]);

            // Example of broadcasting an event (optional)
            event(new InventoryIssueReported($notification));

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'Notification sent successfully!',
                'data' => $notification,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Log the error and return a generic error message
            Log::error('Error notifying admin: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }

    public function getAllNotifications()
    {
        try {
            // Fetch all notifications (you can add filters if needed)
            $notifications = AdminNotification::orderBy('created_at', 'desc')->paginate(10);

            // Return the notifications
            return response()->json([
                'success' => true,
                'message' => 'Notifications retrieved successfully!',
                'data' => $notifications,
            ], 200);
        } catch (\Exception $e) {
            // Log the error and return a generic error message
            Log::error('Error retrieving notifications: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }
}
