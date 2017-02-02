<?php


namespace Nextdots\FractalResponses;

use League\Fractal\TransformerAbstract;
use Illuminate\Http\Response as IlluminateResponse;

trait FractalControllerTrait
{
    protected $statusCode = IlluminateResponse::HTTP_OK;
    protected $headers = [];
    protected $message = 'success';
    protected $errors = [];

    /**
     * @return int
     */
    protected function getStatusCode()
    {
        return property_exists($this, 'sendStatusCode') ? $this->sendStatusCode : $this->statusCode;
    }

    /**
     * @return array
     */
    protected function getHeaders()
    {
        return property_exists($this, 'sendHeaders') ? $this->sendHeaders : $this->headers;
    }

    /**
     * @return string
     */
    protected function getMessage()
    {
        return property_exists($this, 'sendMessage') ? $this->sendMessage : $this->message;
    }

    /**
     * @return array
     */
    protected function getErrors()
    {
        return property_exists($this, 'sendErrors') ? $this->sendErrors : $this->errors;
    }

    /**
     * @param int $statusCode
     * @return $this
     */
    protected function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @param array $headers
     */
    protected function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    /**
     * @param $message
     */
    protected function setMessage($message)
    {
        $this->message;
    }

    /**
     * @param array $errors
     */
    protected function setErrors(array $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @param $data
     * @param TransformerAbstract $transformer
     * @param null $includes
     * @return array
     */
    protected function respondWithItem($data, TransformerAbstract $transformer, $includes = null)
    {
        $fractal = app('Nextdots\FractalResponses\FractalResponses');
        $fractal->parseIncludes($includes);
        $rootScope = $fractal->item($data, $transformer);

        return $this->respondWithArray($rootScope);
    }

    /**
     * @param $data
     * @param TransformerAbstract $transformer
     * @param null $includes
     * @return array
     */
    protected function respondWithCollection($data, TransformerAbstract $transformer, $includes = null)
    {
        $fractal = app('Nextdots\FractalResponses\FractalResponses');
        $fractal->parseIncludes($includes);
        $rootScope = $fractal->collection($data, $transformer);
        return $this->respondWithArray($rootScope);
    }

    /**
     * @param $message
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function respondWithError($message)
    {
        return $this->respondWithArray([
            'error' => [
                'http_code' => $this->getStatusCode(),
                'message' => $message,
            ]
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function errorNotFound()
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_NOT_FOUND)->respondWithError($this->getMessage());
    }

    /**
     * @return array
     */
    protected function sendErrors()
    {
        return [
            'code' => $this->getStatusCode(),
            'message' => $this->getMessage(),
            'errors' => $this->getErrors(),
        ];
    }

    /**
     * @param array $array
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function respondWithArray(array $array)
    {
        $data = [
            'code' => $this->getStatusCode(),
            'message' => $this->getMessage(),
            $array,
        ];
        return response()->json($data, $this->statusCode, $this->getHeaders());
    }
}