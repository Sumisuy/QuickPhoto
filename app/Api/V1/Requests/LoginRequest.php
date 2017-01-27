<?php

namespace App\Api\V1\Requests;

use App\Http\Responses\StandardResponse;
use Config;

class LoginRequest extends ModifierRequests
{
    /**
     * RULES
     * ---
     * @author MS
     * @return mixed
     */
    public function rules()
    {

        return Config::get('boilerplate.login.validation_rules');
    }

    /**
     * AUTHORIZE
     * ---
     * @param StandardResponse $response
     * @author MS
     * @return bool
     */
    public function authorize(StandardResponse $response)
    {
        $this->resetSession($response);
        return true;
    }
}
