<?php

namespace App\Api\V1\Requests\ImageArchive;

use App\Api\V1\Requests\ModifierRequests;
use App\Http\Responses\StandardResponse;
use Config;

class DeleteImageRequest extends ModifierRequests
{
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

    /**
     * RULES
     * ---
     * @author MS
     * @return mixed
     */
    public function rules()
    {
        return Config::get('archive.delete_image.validation_rules');
    }
}
