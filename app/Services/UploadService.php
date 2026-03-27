<?php

namespace App\Services;

use Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UploadService {

    public static function upload($requestFile, $folder) {

        // Dynamic folder
        if (Auth::user() && Auth::user()->school_id) {
            $folder = Auth::user()->school_id.'/'.$folder;
        } else {
            $folder = 'super-admin/'.$folder;
        }

        $extension = strtolower($requestFile->getClientOriginalExtension());
        $file_name = uniqid('', true) . time() . '.' . $extension;

        // ✔ Only compress for student & guardian profile
        if (in_array($extension, ['jpg', 'jpeg', 'png']) 
            && ($folder == 'student' || $folder == 'guardian')
        ) {
            $image = Image::make($requestFile);

            $quality = 80; // start compression from 80%

            do {
                $compressed = $image->encode($extension, $quality);
                $sizeKB = strlen($compressed) / 1024; // convert to KB
                $quality -= 5;
            } while ($sizeKB > 2000 && $quality > 10);  // stop when under 2MB or quality too low

            Storage::disk('public')->put($folder . '/' . $file_name, $compressed);

        } else {
            // default upload
            $requestFile->storeAs($folder, $file_name, 'public');
        }

        return $folder . '/' . $file_name;
    }


    public static function delete($image) {
        if ($image && Storage::disk('public')->exists($image)) {
            return Storage::disk('public')->delete($image);
        }
        return true;
    }
}
