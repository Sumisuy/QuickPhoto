<?php

namespace App\Http\Responses;

class Response extends \Illuminate\Http\Response
{
    protected $response_body;
    protected $download;

    /**
     * ENGAGE
     * ---
     * Sets the response body content, where appropriate headers and json
     * format is selected and sends response.
     * @author MS
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function engage()
    {
        if (isset($this->download)) {

            if (is_file($this->download)) {

                return response()->download($this->download);
            }
            parent::setContent(
                [
                    'status' => 404,
                    'message' => 'Unable to get file for downloading'
                ]
            );
        } elseif (isset($this->response_body)) {

            parent::setContent(self::removeEmpty($this->response_body));
        }
        return parent::send();
    }

    /**
     * ADD BODY
     * ---
     * @param string|object $details
     * @param string $message
     * @param int $status
     * @author MS
     */
    protected function addBody($details, $message = "", $status = 200)
    {
        $this->setStatusCode($status);
        $this->response_body = [
            'message' => $message,
            'body' => $details,
            'guest' => auth()->guest(),
            'modifier' => session()->getId(),
        ];
    }

    /**
     * DATATYPE MESSAGE
     * ---
     * Constructs a default datatype error message for response objects.
     * @param string $type
     * @author MS
     * @return string
     */
    protected function datatypeMessage($type)
    {
        $method = debug_backtrace()[1]['function'];
        return 'Incorrect datatype passed to response object setter: [' .
            $method . '] Expected: [' . $type . ']';
    }

    /**
     * REMOVE EMPTY
     * ---
     * Iterate over multidimensional array and unset all empty elements.
     * @param array $array
     * @author MS
     * @return array
     */
    private function removeEmpty(array $array)
    {
        foreach ($array as $key => $value) {

            if (is_array($value)) {

                $array[$key] = $this->removeEmpty($value);

            } elseif (empty($value)) {

                unset($array[$key]);
            }
        }
        return $array;
    }
}
