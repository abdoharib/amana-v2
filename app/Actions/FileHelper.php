<?php

namespace App\Actions;

use Illuminate\Support\Facades\Storage;

class FileHelper
{
    public function execute($file, $folder)
    {
        // Check if the file is valid
        if (!$file || !$file->isValid()) {
            throw new \Exception('Invalid file');
        }

        // Generate a unique file name
        $fileName = time() . '_' . $file->getClientOriginalName();

        // Save the file to the specified folder
        $path = $file->storeAs($folder, $fileName, 'public');

        // Return the file path
        return $path;
    }


    public function deleteFile($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
