<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class Helpers
{
    public static function upload($directory, $extension, $file)
    {
        $filename = uniqid() . '.' . $extension;
        $file->storeAs($directory, $filename, 'public');
        return $filename;
    }
    

    public static function update($directory, $currentFilename, $extension, $newFile)
    {
        // Delete the current file
        if (Storage::disk('public')->exists($directory . $currentFilename)) {
            Storage::disk('public')->delete($directory . $currentFilename);
        }

        // Upload the new file
        return self::upload($directory, $extension, $newFile);
    }
}
