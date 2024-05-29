<?php

use Illuminate\Support\Facades\File;

if (!function_exists('getRandomAvatar')) {
    /**
     * getRandomAvatar
     *
     * @return string
     */
    function getRandomAvatar(): string
    {
        $directory = public_path('assets/avatars');
        $files = File::files($directory);

        if (empty($files)) {
            return null; // Or a default image path
        }

        $randomFile = $files[array_rand($files)];
        $randomFilePath = str_replace(public_path(), '', $randomFile->getPathname());

        return $randomFilePath;
    }
}
