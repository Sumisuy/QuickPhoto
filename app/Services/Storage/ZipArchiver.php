<?php

namespace App\Services\Storage;

use App\Api\V1\Requests\ImageArchive\StoreImageRequest;
use App\Exceptions\Archive\FailedStoringImage;
use App\User;
use Illuminate\Support\Facades\Storage;

class ZipArchiver
{
    public static $user;
    private $zip_archive;

    /**
     * SET USER
     * ---
     * @author MS
     * @param null|\App\User $user
     */
    public static function setUser($user)
    {
        self::$user = $user;
    }

    /**
     * OVERRIDE USER
     * ---
     * For all those times, like login, where the user object want available
     * during the initial registering in the service container
     * @param User $user
     * @author MS
     */
    public function overrideUser(User $user)
    {
        self::$user = $user;
    }

    /**
     * GET STORAGE LOCATION
     * ---
     * @author MS
     * @return string
     */
    private function getStorageLocation()
    {
        if (self::$user) {
            return '/users/' . (string)self::$user->id . '/tmp/';
        } else {
            return '/guests/' . session()->getId() . '/tmp/';
        }
    }

    /**
     * IS TMP DIRECTORY EMPTY
     * ---
     * @author MS
     * @return bool
     */
    public function isTmpDirectoryEmpty()
    {
        $dir = storage_path('app/public' . $this->getStorageLocation());
        if (file_exists($dir) && is_dir($dir)) {
            $iterator = new \RecursiveDirectoryIterator(
                $dir,
                \FilesystemIterator::SKIP_DOTS
            );
            foreach ($iterator as $file) {
                $extension = pathinfo($file->getPathname(), PATHINFO_EXTENSION);
                if ($extension !== 'zip') {
                    return false;
                }
            }
            return true;
        }
        return true;
    }

    /**
     * NEW ZIP OBJECT
     * ---
     * @param string $zip_full_path_filename
     * @author MS
     */
    private function newZipObject($zip_full_path_filename)
    {
        $this->zip_archive = new \ZipArchive();
        $this->zip_archive->open($zip_full_path_filename, \ZipArchive::CREATE);
    }

    /**
     * ADD IMAGE
     * ---
     * @param string $image_path
     * @author MS
     */
    public function addImage($image_path)
    {
        $filename = pathinfo($image_path, PATHINFO_BASENAME);

        Storage::disk('public')->put(
            $this->getStorageLocation() . $filename,
            file_get_contents($image_path)
        );
    }

    /**
     * ZIP IMAGES
     * ---
     * @author MS
     * @return string
     */
    public function zipImages()
    {
        $dir = storage_path('app/public' . $this->getStorageLocation());
        $zip_file_path = $dir . 'QuickPhoto_' . date('m-d-Y_hia') . '.zip';

        $this->newZipObject($zip_file_path);

        $iterator = new \RecursiveDirectoryIterator(
            $dir,
            \FilesystemIterator::SKIP_DOTS
        );
        foreach ($iterator as $file) {
            $extension = pathinfo($file->getPathname(), PATHINFO_EXTENSION);
            $basename = pathinfo($file->getPathname(), PATHINFO_BASENAME);
            if ($extension === 'zip') {
                continue;
            }
            $this->zip_archive->addFile($file->getPathname(), $basename);
        }

        $this->zip_archive->close();

        foreach ($iterator as $file) {
            $extension = pathinfo($file->getPathname(), PATHINFO_EXTENSION);
            $basename = pathinfo($file->getPathname(), PATHINFO_BASENAME);
            if ($extension === 'zip') {
                continue;
            }
            Storage::delete('public' . $this->getStorageLocation() . $basename);
        }
        return $zip_file_path;
    }
}
