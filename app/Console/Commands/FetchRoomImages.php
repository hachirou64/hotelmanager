<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Room;

class FetchRoomImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rooms:fetch-images {--force : Overwrite existing local images}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch remote room images and save them locally under public/images/rooms/';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $rooms = Room::all();
        if ($rooms->isEmpty()) {
            $this->info('No rooms found.');
            return 0;
        }

        if (!is_dir(public_path('images/rooms'))) {
            mkdir(public_path('images/rooms'), 0755, true);
        }

        foreach ($rooms as $room) {
            $this->info('Processing room ' . $room->numero_chambre . ' (id=' . ($room->id_chambre ?? $room->id) . ')');

            // Only handle when image is a remote URL
            $img = $room->image;
            // If no explicit image set, try the accessor fallback (usually Unsplash)
            if (!$img) {
                $img = $room->image_url;
            }

            if (!$img || !preg_match('/^https?:\/\//i', $img)) {
                $this->line(' - Skipping (no remote image)');
                continue;
            }

            $localPath = 'images/rooms/room-' . ($room->id_chambre ?? $room->id) . '.jpg';
            $fullPath = public_path($localPath);

            if (file_exists($fullPath) && !$this->option('force')) {
                $this->line(' - Already downloaded; use --force to overwrite');
                // update DB to local path if not already
                if ($room->image !== $localPath) {
                    $room->image = $localPath;
                    $room->save();
                    $this->line(' - Updated DB to local path');
                }
                continue;
            }

            try {
                $this->line(' - Downloading ' . $img);
                $resp = Http::timeout(30)->withHeaders(['User-Agent' => 'HotelManagerFetcher/1.0'])->get($img);
                if ($resp->successful()) {
                    file_put_contents($fullPath, $resp->body());
                    $room->image = $localPath;
                    $room->save();
                    $this->info(' - Saved to ' . $localPath);
                } else {
                    $this->error(' - Failed to download (HTTP ' . $resp->status() . ')');
                }
            } catch (\Exception $e) {
                $this->error(' - Error: ' . $e->getMessage());
            }
        }

        $this->info('Done.');
        return 0;
    }
}
