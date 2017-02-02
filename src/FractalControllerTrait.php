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
     * @return array
     */
    protected function getErrors()
    {
        return $this->errors;
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
     * @param int|null $code
     * @param string|null $message
     * @param array $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function respondWithError(int $code = null, string $message = null, array $errors = [])
    {
        return $this->respondWithArray([
            'code' => $code ? $this->statusCode = $code : $this->getStatusCode(),
            'message' => $message ? $this->message = $message : $this->getMessage(),
            'error' => $errors ? $this->errors = $errors : $this->getErrors(),
        ]);
    }

    /**
     * @param string|null $message
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function errorNotFound(string $message = null)
    {
        return $this->respondWithError(IlluminateResponse::HTTP_NOT_FOUND, $message);
    }

    /**
     * @param array $array
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithArray(array $array = [])
    {
        $data = [
            'code' => $this->statusCode,
            'message' => $this->message,
        ];
        $data = array_merge($data, $array);
        return response()->json($data, $this->getStatusCode(), $this->getHeaders());
    }
}