<?php


namespace Nextdots\FractalResponses;

use League\Fractal\TransformerAbstract;

trait FractalControllerTrait
{
    protected $statusCode = 200;

    /**
     * @return int
     */
    protected function getStatusCode()
    {
        return $this->statusCode;
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
                'http_code' => $this->statusCode,
                'message' => $message,
            ]
        ]);
    }

    /**
     * @param string $message
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function errorNotFound($message = 'Resource not found')
    {
        return $this->setStatusCode(404)->respondWithError($message);
    }

    /**
     * @param array $array
     * @param array $headers
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function respondWithArray(array $array, array $headers = [])
    {
        return response()->json($array, $this->statusCode, $headers);
    }
}