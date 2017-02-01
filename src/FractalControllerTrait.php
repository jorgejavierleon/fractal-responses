<?php


namespace Nextdots\FractalResponses;

use League\Fractal\TransformerAbstract;
use Illuminate\Http\Response as IlluminateResponse;

trait FractalControllerTrait
{
    protected $statusCode = IlluminateResponse::HTTP_OK;
    protected $headers = [];
    protected $message = 'success';

    /**
     * @return int
     */
    protected function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return array
     */
    protected function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    protected function getMessage()
    {
        return $this->message;
    }

    /**
     * @param int $statusCode
     * @return $this
     */
    protected function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
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