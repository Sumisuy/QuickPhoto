<?php

namespace App\Exceptions\Archive;

use App\Exceptions\CustomExceptionInterface;

class FailedStoringImage
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
        return 'Application failed to store image in archive';
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
        \Log::info('Application failed to store image in archive');
        \Log::error('FailedStoringImage: ' . $this->message);
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
        $this->message = 'Storage failure, ' . $this->message;
    }
}
