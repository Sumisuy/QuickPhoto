<?php

namespace App\Exceptions\Archive;

use App\Exceptions\CustomExceptionInterface;

class TemporaryZipSpaceInUse
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
        return 'Application failed to build ZIP file, ' .
            'temporary archive space is still being used. ' .
            'If this is in error, please clear temporary archive space by ' .
            'using the "Clear Temporary Archive" in your account area, ' .
            'and then try to download again.';
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
        \Log::info('Application failed to build ZIP file');
        \Log::error('TemporaryZipSpaceInUse: ' . $this->message);
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
        $this->message = 'Temporary ZIP storage in use, ' . $this->message;
    }
}
