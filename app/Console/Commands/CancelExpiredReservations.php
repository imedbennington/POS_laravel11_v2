<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Models\Setting;
use Carbon\Carbon;
class CancelExpiredReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    //protected $signature = 'app:cancel-expired-reservations';
    protected $signature = 'reservations:cancel-expired';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel reservations if the client does not arrive within the defined countdown time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the countdown time from settings (default to 30 minutes)
        $countdownTime = Setting::get('reservation_expiration_time', 30);

        // Get all expired pending reservations
        $expiredReservations = Reservation::where('status', 'pending')
            ->where('expires_at', '<', Carbon::now())
            ->get();

        foreach ($expiredReservations as $reservation) {
            $reservation->update(['status' => 'cancelled']);
        }

        $this->info("Expired reservations have been cancelled.");

    }
}
