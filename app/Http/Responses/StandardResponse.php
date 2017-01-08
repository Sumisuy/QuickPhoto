<?php

namespace App\Http\Responses;

class StandardResponse extends Response implements ResponseInterface
{
    private $status;
    private $message;
    private $details = '';

    /**
     * ENGAGE
     * ---
     * @author MS
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function engage()
    {
        if (isset($this->status) && isset($this->message)) {

            $this->response_body = [
                'status'  => $this->status,
                'message' => $this->message,
                'details' => $this->details,
            ];
            return parent::engage();
        }
        throw new \Exception(parent::MISSING_RESPONSE_PARAMETERS);
    }

    /**
     * SUCCESS
     * ---
     * @author MS
     * @param bool $status
     * @author MS
     * @return StandardResponse
     * @throws \Exception
     */
    public function success($status)
    {
        if (is_bool($status)) {

            if ($status) {

                $this->status = 'success';

                return $this;
            }
            $this->status = 'fail';

            return $this;
        }
        throw new \Exception($this->datatypeMessage('bool'));
    }

    /**
     * SET MESSAGE
     * ---
     * @author MS
     * @param string $message
     * @author MS
     * @return StandardResponse
     * @throws \Exception
     */
    public function setMessage($message)
    {
        if (is_string($message)) {

            $this->message = $message;

            return $this;
        }
        throw new \Exception($this->datatypeMessage('string'));
    }

    /**
     * SET DETAILS
     * ---
     * @author MS
     * @param string $details
     * @author MS
     * @return StandardResponse
     * @throws \Exception
     */
    public function setDetails($details)
    {
        if (is_string($details)) {

            $this->details = $details;

            return $this;
        }
        throw new \Exception($this->datatypeMessage('string'));
    }
}
