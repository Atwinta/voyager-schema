<?php

namespace Atwinta\Voyager\Schema\Abstracts;


use Illuminate\Database\Eloquent\Model;

/**
 * Interface DataTypeInterface
 * @package Atwinta\Voyager\Schema\Abstracts
 */
interface DataTypeInterface
{
    /**
     * @return Model
     */
    public function table(): Model;

    /**
     * @return array
     */
    public function getDataTypeArray(): array;

    /**
     * @return array
     */
    public function getDataRowsArray(): array;
}