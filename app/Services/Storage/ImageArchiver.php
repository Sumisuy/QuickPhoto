<?php

namespace App\Services\Storage;

use App\Api\V1\Requests\ImageArchive\StoreImageRequest;
use App\Exceptions\Archive\FailedStoringImage;
use App\Exceptions\Archive\FileNotFound;
use App\User;
use Illuminate\Support\Facades\Storage;

class ImageArchiver
{
    public static $user;

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
            return '/users/' . (string)self::$user->id . '/images/';
        } else {
            return '/guests/' . session()->getId() . '/images/';
        }
    }

    /**
     * GET DOWNLOAD LOCATION
     * ---
     * @author MS
     * @return string
     */
    private function getDownloadLocation()
    {
        return '/downloads/guests/' . session()->getId() . '/';
    }

    /**
     * MOVE FILE TO DOWNLOADS
     * ---
     * @param string $path
     * @author MS
     */
    public function moveFileToDownloads($path)
    {
        $filename = pathinfo($path, PATHINFO_BASENAME);
        Storage::disk('public')->put(
            $this->getDownloadLocation() . $filename,
            file_get_contents($path)
        );
        return Storage::disk('public')
            ->url($this->getDownloadLocation() . $filename);
    }

    /**
     * GET GUEST STORAGE LOCATION
     * ---
     * @author MS
     * @return string
     */
    private function getGuestStorage()
    {
        return '/guests/' . session()->getId() . '/images/';
    }

    /**
     * GET GUEST ARCHIVE
     * ---
     * @author MS
     * @return string
     */
    private function getGuestArchive()
    {
        return '/guests/' . session()->getId();
    }

    /**
     * GET ARCHIVE
     * ---
     * Get archive location
     * @author MS
     * @return string
     */
    private function getArchive()
    {
        if (self::$user) {
            return '/users/' . (string)self::$user->id;
        } else {
            return $this->getGuestArchive();
        }
    }

    /**
     * GET STORAGE PATH FROM FILENAME
     * ---
     * @param string $filename
     * @return string
     * @throws FileNotFound
     */
    public function getStoragePathFromFilename($filename)
    {
        $file_check = storage_path(
            'app/public' . $this->getStorageLocation() . $filename
        );
        $guest_check = storage_path(
            'app/public' . $this->getGuestStorage() . $filename
        );
        if (file_exists($file_check) && !is_dir($file_check)) {

            return 'public' . $this->getStorageLocation() . $filename;
        }
        if (file_exists($guest_check) && !is_dir($guest_check)) {

            return 'public' . $this->getGuestStorage() . $filename;
        }
        throw new FileNotFound($filename . ' no longer exists in archive');
    }

    /**
     * GET FULL PATH FROM FILENAME
     * ---
     * @param string $filename
     * @author MS
     * @return string
     */
    public function getFullPathFromFilename($filename)
    {
        return storage_path(
            'app/' . $this->getStoragePathFromFilename($filename)
        );
    }

    /**
     * STORE IMAGE
     * ---
     * @param StoreImageRequest $request
     * @author MS
     * @return string
     * @throws FailedStoringImage
     */
    public function storeImage(StoreImageRequest $request)
    {
        try {
            $path = $request->file('file')->storeAs(
                $this->getStorageLocation(),
                pathinfo(
                    $request->file('file')->getClientOriginalName(),
                    PATHINFO_BASENAME
                ),
                'public'
            );

        } catch (\Exception $ex) {
            throw new FailedStoringImage(
                'Error during image storage: ' . $ex->getMessage()
            );
        }
        if ($path === false) {
            throw new FailedStoringImage(
                'Storage request for image returned false'
            );
        }
        return $path;
    }

    /**
     * USER HAS GUEST CONTENT
     * ---
     * @author MS
     * @return bool
     */
    public function userHasGuestContent()
    {
        $storage = storage_path(
            'app/public' . $this->getGuestStorage()
        );
        if (!(file_exists($storage) && is_dir($storage))) {
            return false;
        }
        $dir = new \RecursiveDirectoryIterator(
            $storage,
            \FilesystemIterator::SKIP_DOTS
        );
        foreach ($dir as $image) {
            return true;
        }
        return false;
    }

    /**
     * TRANSFER GUEST CONTENT TO USER
     * ---
     * Moves the contents of the users guest archive to the users archive and
     * cleans up the old folders from the guest archive
     * @author MS
     * @return bool
     */
    public function transferGuestContentToUser()
    {
        $storage = storage_path(
            'app/public' . $this->getGuestStorage()
        );
        $user_storage = storage_path(
            'app/public' . $this->getStorageLocation()
        );
        if (!(file_exists($storage) && is_dir($storage))) {
            return false;
        }
        $dir = new \RecursiveDirectoryIterator(
            $storage,
            \FilesystemIterator::SKIP_DOTS
        );
        foreach ($dir as $image) {
            $filename = pathinfo($image->getPathname(), PATHINFO_BASENAME);

            if ($image->getPathname() === $user_storage . $filename) {
                continue;
            }
            if (file_exists($user_storage . $filename)) {
                continue;
            }
            Storage::move(
                'public' . $this->getGuestStorage() . $image->getBasename(),
                'public' . $this->getStorageLocation() . $filename
            );
        }
        Storage::deleteDirectory('public' . $this->getGuestArchive());

        return true;
    }

    /**
     * DELETE FILE
     * ---
     * @param string $file
     * @author MS
     * @return bool
     */
    public function deleteFile($file)
    {
        $path = storage_path(
            'app/public' . $this->getStorageLocation() . $file
        );
        $delete = 'public' . $this->getStorageLocation() . $file;
        if (file_exists($path) && !is_dir($path)) {
            Storage::delete($delete);
            return true;
        }
        return false;
    }

    /**
     * DELETE ALL
     * ---
     * @author MS
     */
    public function deleteAll()
    {
        if ($this->userHasGuestContent()) {
            Storage::deleteDirectory('public' . $this->getGuestArchive());
        }
        Storage::deleteDirectory('public' . $this->getArchive());
    }

    /**
     * GET ALL IMAGE INFO
     * ---
     * @author MS
     * @return \ArrayObject
     */
    public function getAllImageInfo()
    {
        $images = new \ArrayObject([]);
        $storage = storage_path('app/public' . $this->getStorageLocation());

        if (!(file_exists($storage) && is_dir($storage))) {
            return $images;
        }

        $dir = new \RecursiveDirectoryIterator(
            $storage,
            \FilesystemIterator::SKIP_DOTS
        );
        foreach ($dir as $image) {
            $filename = pathinfo($image->getPathname(), PATHINFO_BASENAME);
            $images->offsetSet(
                $filename,
                [
                    'url' => Storage::disk('public')
                        ->url($this->getStorageLocation() . $filename),
                    'file_size' => Storage::size(
                        'public' . $this->getStorageLocation() . $filename
                    )
                ]
            );
        }
        return $images;
    }

    /**
     * GET ALL IMAGE PATHS
     * ---
     * @author MS
     * @return array
     */
    public function getAllImagePaths()
    {
        $images = [];
        $storage = storage_path('app/public' . $this->getStorageLocation());

        if (!(file_exists($storage) && is_dir($storage))) {
            return $images;
        }
        $dir = new \RecursiveDirectoryIterator(
            $storage,
            \FilesystemIterator::SKIP_DOTS
        );
        foreach ($dir as $image) {
            $filename = pathinfo($image->getPathname(), PATHINFO_BASENAME);
            $images[] = $storage . $filename;
        }
        return $images;
    }

    /**
     * ADD IMAGE
     * ---
     * @param string $image_path
     * @author MS
     * @return string
     */
    public function addImage($image_path)
    {
        $filename = pathinfo($image_path, PATHINFO_BASENAME);

        Storage::disk('public')->put(
            $this->getStorageLocation() . $filename,
            file_get_contents($image_path)
        );
        unlink($image_path);

        return Storage::disk('public')
            ->url($this->getStorageLocation() . $filename);
    }
}
