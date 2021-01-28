<?php

namespace Atwinta\Voyager\Schema;

use Atwinta\Voyager\Schema\Abstracts\DataTypeInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseDataType
 * @package Atwinta\Voyager\Schema
 */
abstract class BaseDataType implements DataTypeInterface
{
    protected $model;

    /**
     * @return string
     */
    abstract protected function model(): string;

    /**
     * @return Model
     */
    public function table(): Model
    {
        $model = $this->model();
        return ($this->model ?? ($this->model = new $model));
    }

    /**
     * @param string $prefix
     * @param \Illuminate\Support\Collection|array $keys
     * @param string|null $locale
     * @return array
     */
    protected function getOptionList(string $prefix, $keys, string $locale = null)
    {
        $list = [];
        foreach ($keys as $key) {
            $list[$key] = __("{$prefix}.{$key}", [], $locale);
        }
        return $list;
    }
}