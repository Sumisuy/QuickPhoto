<?php

namespace App\Exceptions\Response;

use App\Exceptions\CustomExceptionInterface;

class ResponseFailedValidation
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
        return 'Application failed to build response due to failed validation';
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
        \Log::info(
            'Application failed to build response due to failed validation'
        );
        \Log::error('ResponseFailedValidation: ' . $this->message);
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
        $this->message = 'Validation of required response fields failed: ' .
            $this->message;
    }
}
