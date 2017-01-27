<?php

namespace App\Exceptions\Archive;

use App\Exceptions\CustomExceptionInterface;

class FileNotFound
    extends \Exception
    implements CustomExceptionInterface
{
    /**
     * NICE MESSAGE
     * ---
     * Return an error message that can be shown to a user
     * @author MS
     * @return string
     */
    public function niceMessage()
    {
        $this->updateMessage();
        $this->logException();
        return 'Application failed to locate file in the archive';
    }

    /**
     * LOG EXCEPTION
     * ---
     * To trigger any custom logging methods that you may wish to fire off
     * @author MS
     * @return void
     */
    private function logException()
    {
        \Log::info('Application failed to locate file in the archive');
        \Log::error('FileNotFound: ' . $this->message);
    }

    /**
     * UPDATE MESSAGE
     * ---
     * Overwrite \Exception message to contain standard exception details as
     * well as context provided upon instantiation
     * @author MS
     * @return void
     */
    private function updateMessage()
    {
        $this->message = 'File location failure, ' . $this->message;
    }
}
