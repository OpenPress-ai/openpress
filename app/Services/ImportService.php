<?php

namespace App\Services;

class ImportService
{
    public function importJson(string $path): void
    {
        $json = json_decode(file_get_contents($path), true);

        foreach ($json as $item) {
            // Import each item
        }
    }
}
