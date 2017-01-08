<?php

namespace App\Http\Responses;

class DownloadResponse extends Response implements ResponseInterface
{
    private $file_path;

    /**
     * ADD FILE
     * ---
     * @param string $file_path
     * @author MS
     * @returns DownloadResponse
     * @throws \Exception
     */
    public function addFile($file_path)
    {

        if (is_file($file_path)) {

            $this->file_path = $file_path;

            return $this;
        }
        throw new \Exception(parent::MALFORMED_FILE_PATH);
    }

    /**
     * ENGAGE
     * ---
     * @author MS
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function engage()
    {
        if (isset($this->file_path)) {

            $this->download = $this->file_path;

            return parent::engage();
        }
        throw new \Exception(parent::MISSING_RESPONSE_PARAMETERS);
    }
}