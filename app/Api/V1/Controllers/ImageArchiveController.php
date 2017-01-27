<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\ImageArchive\DeleteAllImagesRequest;
use App\Api\V1\Requests\ImageArchive\DeleteImageRequest;
use App\Api\V1\Requests\ImageArchive\DownloadImagesRequest;
use App\Api\V1\Requests\ImageArchive\GetAllImagesRequest;
use App\Api\V1\Requests\ImageArchive\StoreImageRequest;
use App\Exceptions\Archive\FailedStoringImage;
use App\Exceptions\Archive\FileNotFound;
use App\Exceptions\Archive\TemporaryZipSpaceInUse;
use App\Http\Controllers;
use App\Http\Responses\DownloadResponse;
use App\Http\Responses\StandardResponse;
use App\Services\Storage\ImageArchiver;
use App\Services\Storage\ZipArchiver;

class ImageArchiveController extends Controllers\Controller
{
    /**
     * INDEX
     * ---
     * Get a list of all public, relative, urls to all images in a user or
     * guests unique storage space
     * @param GetAllImagesRequest $request
     * @param ImageArchiver $archive
     * @param StandardResponse $response
     * @author MS
     * @return StandardResponse
     */
    public function index(
        GetAllImagesRequest $request,
        ImageArchiver $archive,
        StandardResponse $response
    ) {
        $response->setDetails(
            'image_list',
            $archive->getAllImageInfo()->getArrayCopy()
        )->selectObject()->engage();
    }

    /**
     * STORE
     * ---
     * Upload an image and store it in the user or guest unique storage space
     * @param StoreImageRequest $request
     * @param ImageArchiver $archive
     * @param StandardResponse $response
     * @author MS
     * @return StandardResponse
     */
    public function store(
        StoreImageRequest $request,
        ImageArchiver $archive,
        StandardResponse $response
    ) {
        try {
            $response->setDetails(
                'path',
                '/storage/' . $archive->storeImage($request)
            );
        } catch (FailedStoringImage $ex) {

            $response->setStatus(500)
                ->setMessage('Error')
                ->setDetails('message', $ex->niceMessage());

        } catch (\Exception $exception) {

            $this->unknownErrorResponse($exception, $response);
        }
        $response->selectObject()->engage();
    }

    /**
     * DESTROY
     * ---
     * @param int $id
     * @param DeleteImageRequest $request
     * @param ImageArchiver $archive
     * @param StandardResponse $response
     * @author MS
     * @return StandardResponse
     */
    public function destroy(
        $id,
        DeleteImageRequest $request,
        ImageArchiver $archive,
        StandardResponse $response
    ) {
        $archive->deleteFile($id);
        $response->setDetails(
            'message',
            'The file, ' . $id . ', has been successfully deleted'
        )->selectObject()->engage();
    }

    /**
     * DELETE ALL
     * ---
     * @param DeleteAllImagesRequest $request
     * @param ImageArchiver $archive
     * @param StandardResponse $response
     * @author MS
     * @return StandardResponse
     */
    public function deleteAll(
        DeleteAllImagesRequest $request,
        ImageArchiver $archive,
        StandardResponse $response
    ) {
        $archive->deleteAll();
        $response->setDetails(
            'message',
            'All image files have been successfully deleted'
        )->selectObject()->engage();
    }

    /**
     * DOWNLOAD IMAGE
     * ---
     * @param string $filename
     * @param DownloadImagesRequest $request
     * @param ImageArchiver $archive
     * @param StandardResponse $response
     * @author MS
     * @return DownloadResponse
     */
    public function downloadImage(
        $filename,
        DownloadImagesRequest $request,
        ImageArchiver $archive,
        StandardResponse $response
    ) {
        $response->setDetails(
            'url',
            $archive->moveFileToDownloads(
                $archive->getFullPathFromFilename($filename)
            )
        )->selectObject()->engage();
    }

    /**
     * DOWNLOAD SELECTED IMAGES
     * ---
     * @param DownloadImagesRequest $request
     * @param ImageArchiver $archive
     * @param ZipArchiver $zip
     * @param StandardResponse $response
     * @author MS
     * @return DownloadResponse
     */
    public function downloadSelectedImages(
        DownloadImagesRequest $request,
        ImageArchiver $archive,
        ZipArchiver $zip,
        StandardResponse $response
    ) {
        try {
            if ($zip->isTmpDirectoryEmpty()) {
                foreach ($request->filenames as $filename) {
                    $zip->addImage(
                        $archive->getFullPathFromFilename($filename)
                    );
                }
            }
        } catch (TemporaryZipSpaceInUse $temporaryZipSpaceInUse) {

            $response->setStatus(409)
                ->setMessage('Conflict')
                ->setDetails('message', $temporaryZipSpaceInUse->niceMessage());
            die;
        } catch (FileNotFound $fileNotFound) {

            $response->setStatus(500)
                ->setMessage('Error')
                ->setDetails('message', $fileNotFound->niceMessage());

        } catch (\Exception $exception) {

            $this->unknownErrorResponse($exception, $response);
        }
        $zip_file = $zip->zipImages();
        $response->setDetails('url', $archive->moveFileToDownloads($zip_file))
            ->selectObject()->engage();
    }

    /**
     * DOWNLOAD ALL IMAGES
     * ---
     * @param DownloadImagesRequest $request
     * @param ImageArchiver $archive
     * @param ZipArchiver $zip
     * @param StandardResponse $response
     * @author MS
     * @return StandardResponse
     */
    public function downloadAllImages(
        DownloadImagesRequest $request,
        ImageArchiver $archive,
        ZipArchiver $zip,
        StandardResponse $response
    ) {
        try {
            if ($zip->isTmpDirectoryEmpty()) {
                foreach ($archive->getAllImagePaths() as $image) {
                    $zip->addImage($image);
                }
            }
        } catch (TemporaryZipSpaceInUse $temporaryZipSpaceInUse) {

            $response->setStatus(409)
                ->setMessage('Conflict')
                ->setDetails('message', $temporaryZipSpaceInUse->niceMessage());
            die;
        } catch (FileNotFound $fileNotFound) {

            $response->setStatus(500)
                ->setMessage('Error')
                ->setDetails('message', $fileNotFound->niceMessage());

        } catch (\Exception $exception) {

            $this->unknownErrorResponse($exception, $response);
        }
        $zip_file = $zip->zipImages();
        $response->setDetails('url', $archive->moveFileToDownloads($zip_file))
            ->selectObject()->engage();
    }
}
