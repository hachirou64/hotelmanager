<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Room;
use App\Models\Reservation;

class RefreshRoomsStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rooms:refresh-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate and update the statut field for all rooms based on reservations';

    public function handle()
    {
        $this->info('Refreshing room statuses...');

        $rooms = Room::all();
        $today = now()->toDateString();

        foreach ($rooms as $room) {
            try {
                $hasActiveToday = Reservation::where('id_chambre', $room->id_chambre)
                    ->where('statut', '!=', 'annulée')
                    ->where('date_debut', '<=', $today)
                    ->where('date_fin', '>', $today)
                    ->exists();

                $hasFuture = Reservation::where('id_chambre', $room->id_chambre)
                    ->where('statut', '!=', 'annulée')
                    ->where('date_debut', '>', $today)
                    ->exists();

                $newStatus = 'libre';
                if ($hasActiveToday) {
                    $newStatus = 'occupée';
                } elseif ($hasFuture) {
                    $newStatus = 'réservée';
                }

                if ($room->statut !== $newStatus) {
                    $room->statut = $newStatus;
                    $room->save();
                }
            } catch (\Exception $e) {
                report($e);
                $this->error('Failed to refresh room ' . $room->id_chambre . ': ' . $e->getMessage());
            }
        }

        $this->info('Room statuses refreshed.');
        return 0;
    }
}
