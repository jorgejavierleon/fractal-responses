<?php


namespace Nextdots\FractalResponses;

use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Serializer\SerializerAbstract;
use League\Fractal\TransformerAbstract;

class FractalResponses
{
    /**
     * @var Manager
     */
    private $manager;
    /**
     * @var SerializerAbstract
     */
    private $serializer;
    /**
     * @var Request
     */
    private $request;

    /**
     * FractalResponse constructor.
     * @param Manager $manager
     * @param SerializerAbstract $serializer
     * @param Request $request
     */
    public function __construct(
        Manager $manager,
        SerializerAbstract $serializer,
        Request $request
    ) {
        $this->manager = $manager;
        $this->serializer = $serializer;
        $this->manager->setSerializer($serializer);
        $this->request = $request;
    }

    /**
     * @param null $includes
     */
    public function parseIncludes($includes = null)
    {
        if (empty($includes)) {
            $includes = $this->request->query('include', '');
        }

        $this->manager->parseIncludes($includes);
    }

    /**
     * @param $data
     * @param TransformerAbstract $transformer
     * @return array
     */
    public function item($data, TransformerAbstract $transformer)
    {
        return $this->createDataArray(new Item($data, $transformer));
    }

    /**
     * @param $data
     * @param TransformerAbstract $transformer
     * @return array
     */
    public function collection($data, TransformerAbstract $transformer)
    {
        return $this->createDataArray(new Collection($data, $transformer));
    }

    /**
     * @param ResourceInterface $resource
     * @return array
     */
    private function createDataArray(ResourceInterface $resource)
    {
        return $this->manager->createData($resource)->toArray();
    }
}