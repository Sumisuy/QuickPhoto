<?php

namespace App\Http\Responses;

class Response extends \Illuminate\Http\Response
{
    const MISSING_RESPONSE_PARAMETERS = 'Could not respond to Ajax request. Response object missing required parameters';
    const MALFORMED_FILE_PATH = 'The file path provided on response object is either malformed or invalid';

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
            $this->setContent(
                [
                    'status' => 'fail',
                    'message' => 'Unable to get file for downloading'
                ]
            );
        } elseif (isset($this->response_body)) {

            $this->setContent($this->removeEmpty($this->response_body));
        }
        return parent::send();
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
