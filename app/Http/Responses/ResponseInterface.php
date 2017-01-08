<?php

namespace App\Http\Responses;

interface ResponseInterface
{
    /**
     * ENGAGE
     * ---
     * engage() MUST prepare the Response property "$response_body" and then
     * fire off and return the parent::engage.
     * @author MS
     * @return mixed
     */
    public function engage();
}