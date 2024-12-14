<?php

namespace App\Http\Controllers;

use App\Models\FoodDrink;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Payment;
use Stripe\Stripe;
use Stripe\Charge;
class PaymentController extends Controller
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
    public function store(Request $request, $clientId, $itemId)
    {
        try {
            $validated = $request->validate([
                'payment_method' => 'required|string',
                'amount' => 'required|numeric|min:0.01',
            ]);

            // Retrieve the client and the item
            $client = Client::findOrFail($clientId);
            $item = FoodDrink::findOrFail($itemId);

            // Validate the amount if necessary (e.g., matches the item's price)
            if ($validated['amount'] != $item->price) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment amount does not match item price.',
                ], 400);
            }

            // Record the payment
            $payment = Payment::create([
                'client_id' => $client->id,
                'item_id' => $item->id,
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
            ]);

            // Optionally update the client's debt or other related fields
            $client->debt -= $validated['amount'];
            $client->save();

            return response()->json([
                'success' => true,
                'message' => 'Payment successfully processed.',
                'payment' => $payment,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Client or item not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during payment processing.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    protected function processPayment(array $data)
{
    try {
        // Example for a payment gateway integration
        $transactionId = 'txn_' . uniqid(); // Simulated transaction ID

        // Simulate successful payment (replace with actual API calls)
        if ($data['payment_method'] === 'credit_card') {
            return [
                'success' => true,
                'transaction_id' => $transactionId,
            ];
        }

        // Simulate payment failure
        return [
            'success' => false,
            'error_message' => 'Payment method not supported or insufficient funds.',
        ];
    } catch (\Exception $e) {
        return [
            'success' => false,
            'error_message' => 'Payment gateway error: ' . $e->getMessage(),
        ];
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
