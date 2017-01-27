<?php

namespace App\Api\V1\Requests;

use Config;
use Dingo\Api\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    /**
     * RULES
     * ---
     * @author MS
     * @return mixed
     */
    public function rules()
    {
        return Config::get('boilerplate.forgot_password.validation_rules');
    }

    /**
     * AUTHORIZE
     * ---
     * @author MS
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
