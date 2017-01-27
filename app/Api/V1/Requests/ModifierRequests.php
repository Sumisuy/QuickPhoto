<?php

namespace App\Api\V1\Requests;

use App\Exceptions\Auth\CannotDetermineUser;
use App\Http\Responses\StandardResponse;
use Illuminate\Foundation\Http\FormRequest;

/**
 * CLASS MODIFIER REQUESTS
 * ---
 * Making your custom Request objects extend from this class, and calling the
 * resetSession() method - I've been putting it in the authorize() method - will
 * ensure that your requests contain a 'modifier', allowing users to keep their
 * session over the API...
 *
 * ... Which is a requirement for some services such as guest Storage when
 * a user does not have or log in to a user account, but still wants to use
 * basic features of the application.
 *
 * @package App\Api\V1\Requests
 * @author MS
 */
class ModifierRequests extends FormRequest
{
    /**
     * RESET SESSION
     * ---
     * Call this method from any custom Request object that - usually from the
     * authorize() method.
     *
     * @param StandardResponse $response
     * @author MS
     */
    protected function resetSession(StandardResponse $response)
    {
        try {
            if (!$this->input('modifier') || empty($this->input('modifier'))) {
                throw new CannotDetermineUser(
                    'Modifier not present on request'
                );
            }
        } catch (CannotDetermineUser $ex) {

            $response->setStatus(400);
            $response->setMessage('Bad Request Error');
            $response->setDetails('cause', $ex->niceMessage());
            $response->selectObject()->engage();
            die;
        }
        session()->setId($this->input('modifier'));
    }
}
