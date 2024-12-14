<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Table;
use App\Models\Reservation;
use App\Models\Setting;
class ReservationController extends Controller
{

/*public function makeReservation(Request $request)
{
    $request->validate([
        'table_id' => 'required|exists:tables,id',
        'client_id'=>'required|exists:clients,id',
        'reservation_time' => 'required|date|after:now',
    ]);

    $table = Table::find($request->table_id);

    // Check if the table already has a pending reservation
    if ($table->reservation) {
        return response()->json([
            'message' => 'The selected table is already reserved.',
        ], 422);
    }

    $reservation = Reservation::create([
        //'client_id' => auth()->id(),
        'client_id' => 1,
        'table_id' => $request->table_id,
        'reservation_time' => $request->reservation_time,
    ]);

    return response()->json([
        'message' => 'Reservation created successfully!',
        'reservation' => $reservation,
    ]);
}*/

public function makeReservation(Request $request)
{
    // Validate the input data
    $request->validate([
        'table_id' => 'required|exists:tables,id',
        'reservation_time' => 'required|date|after:now',
    ]);

    // Fetch the table
    $table = Table::find($request->table_id);

    // Check if the table is already reserved
    if ($table->status == 'reserved') {
        return response()->json(['message' => 'The table is already reserved.'], 422);
    }

    // Get the countdown time from the admin's settings
    $countdownTime = Setting::get('reservation_expiration_time', 30); // default to 30 minutes

    // Calculate the expiration time
    $expiresAt = Carbon::parse($request->reservation_time)->addMinutes($countdownTime);

    // Create the reservation
    $reservation = Reservation::create([
        'client_id' => auth()->id(),
        //'client_id' => 1,
        'table_id' => $table->id,
        'reservation_time' => $request->reservation_time,
        'status' => 'pending',
        'expires_at' => $expiresAt,
    ]);

    // Update the table status to reserved
    //$table->update(['status' => 'reserved']);
    $table->status = 'reserved';
    $table->save();

    return response()->json(['message' => 'Reservation created successfully!', 'reservation' => $reservation]);
}


}
