<?php

namespace App\Http\Responses;

class DownloadResponse extends Response
{
    private $file_path;

    /**
     * ADD FILE
     * ---
     * @param string $file_path
     * @author MS
     * @returns DownloadResponse
     */
    public function addFile($file_path)
    {
        if (is_file($file_path)) {
            $headers = array(
                header('Content-Type: application/zip', 'Content-Disposition: attachment;')
            );
            $this->withHeaders($headers);
            $this->file_path = $file_path;
            $this->download = $file_path;
            
            return $this;
        }
    }
}
