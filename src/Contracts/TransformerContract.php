<?php
/**
 * Created by PhpStorm.
 * User: agrisales
 * Date: 1/02/17
 * Time: 03:26 PM
 */

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