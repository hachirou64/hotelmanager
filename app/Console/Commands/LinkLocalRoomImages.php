<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Room;

class LinkLocalRoomImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rooms:link-local-images {--apply : Apply changes to DB (default is dry-run)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan public/images/rooms and link files to rooms by filename (dry-run by default)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dir = public_path('images/rooms');
        if (!is_dir($dir)) {
            $this->error('Directory not found: ' . $dir);
            return 1;
        }

        $files = array_values(array_filter(scandir($dir), function ($f) use ($dir) {
            return is_file($dir . DIRECTORY_SEPARATOR . $f) && preg_match('/\.(jpg|jpeg|png|webp|svg)$/i', $f);
        }));

        if (empty($files)) {
            $this->info('No image files found in ' . $dir);
            return 0;
        }

        $this->info('Found ' . count($files) . ' files. Attempting to match by filename patterns...');

        $rooms = Room::all()->keyBy(function ($r) { return (string)$r->numero_chambre; });

        $mapped = [];
        foreach ($files as $file) {
            $name = pathinfo($file, PATHINFO_FILENAME);
            // try several patterns: exact numero, room-101, id-1, room_101
            $candidates = [];
            if (preg_match('/^(?:room[-_]?|chambre[-_]?|id[-_]?|)(\d+)$/i', $name, $m)) {
                $candidates[] = $m[1];
            }
            // also try filenames containing numbers
            if (preg_match_all('/(\d{2,4})/', $name, $m2)) {
                foreach ($m2[1] as $num) $candidates[] = $num;
            }

            $candidates = array_unique($candidates);
            $matchedRoom = null;
            foreach ($candidates as $cand) {
                if (isset($rooms[(string)$cand])) {
                    $matchedRoom = $rooms[(string)$cand];
                    break;
                }
            }

            if ($matchedRoom) {
                $localPath = 'images/rooms/' . $file;
                $mapped[] = [$file, $matchedRoom->numero_chambre, $localPath, $matchedRoom->image ?? ''];
                $this->line("MATCH: {$file} → room {$matchedRoom->numero_chambre} (current image: " . ($matchedRoom->image ?: 'none') . ")");
                if ($this->option('apply')) {
                    $matchedRoom->image = $localPath;
                    $matchedRoom->save();
                    $this->info(' -> Applied');
                }
            } else {
                $this->line("NO MATCH: {$file}");
            }
        }

        $this->info('Done. ' . count($mapped) . ' file(s) matched.');
        if (empty($mapped)) {
            $this->line('If you have images, use filenames like `101.jpg`, `room-101.jpg` or `id-1.jpg` to match room numbers.');
        }

        return 0;
    }
}
