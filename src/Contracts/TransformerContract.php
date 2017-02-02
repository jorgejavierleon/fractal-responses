<?php

namespace Nextdots\FractalResponses\Contracts;

use Illuminate\Database\Eloquent\Model;
interface TransformerContract
{
    /**
     * @param Model $model
     * @return mixed
     */
    public function transform(Model $model);
}