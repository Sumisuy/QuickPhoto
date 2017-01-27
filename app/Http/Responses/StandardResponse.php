<?php

namespace App\Http\Responses;

use App\Exceptions\Response\ResponseFailedValidation;
use Config;

class StandardResponse extends Response
{
    private $details;
    private $message = 'success';
    private $status = 200;

    /**
     * SELECT JSON
     * ---
     * @author MS
     * @return StandardResponse
     */
    public function selectJson()
    {
        $this->addBody(
            json_encode($this->details),
            $this->message,
            $this->status
        );
        return $this;
    }

    /**
     * SELECT OBJECT
     * ---
     * @author MS
     * @return StandardResponse
     */
    public function selectObject()
    {
        $this->addBody(
            $this->details,
            $this->message,
            $this->status
        );
        return $this;
    }

    /**
     * VALIDATE DETAILS OBJECT
     * ---
     * @param Config $configuration
     * @author MS
     * @return bool|string
     * @throws \Exception
     */
    private function validateDetailsObject(Config $configuration)
    {
        foreach ($configuration as $property => $validation) {
            if (property_exists($this->details, $property)) {
                continue;
            }
            return $property;
        }
        return true;
    }

    /**
     * SELECT JSON WITH CONFIG
     * ---
     * @param string $config
     * @author MS
     * @return StandardResponse
     * @throws ResponseFailedValidation
     */
    public function selectJsonWithConfig($config)
    {
        if ($failed = $this->validateDetailsObject(Config::get($config))) {
            throw new ResponseFailedValidation(
                'StandardResponse::selectJsonWithConfig() [' .
                $failed .
                '] required property not set on response object'
            );
        }
        return $this->selectJson();
    }

    /**
     * SELECT OBJECT WITH CONFIG
     * ---
     * @param string $config
     * @author MS
     * @return StandardResponse
     * @throws ResponseFailedValidation
     */
    public function selectObjectWithConfig($config)
    {
        if ($failed = $this->validateDetailsObject(Config::get($config))) {
            throw new ResponseFailedValidation(
                'StandardResponse::selectObjectWithConfig() [' .
                $failed .
                '] required property not set on response object'
            );
        }
        return $this->selectObject();
    }

    /**
     * SET DETAILS
     * ---
     * @param string $property
     * @param mixed  $value
     * @author MS
     * @return StandardResponse
     */
    public function setDetails($property, $value)
    {
        if ($this->details === null) {
            $this->details = new \stdClass();
        }
        $this->details->{$property} = $value;
        return $this;
    }

    /**
     * SET MESSAGE
     * ---
     * @param string $message
     * @author MS
     * @return StandardResponse
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * SET STATUS
     * ---
     * @param int $status
     * @author MS
     * @return StandardResponse
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
}
