<?php

namespace Tests\Concerns;

trait CreatesModels
{
    /**
     * @param        $class
     * @param  array $attributes
     * @param  null  $times
     * @return mixed
     */
    protected function make($class, $attributes = [], $times = null)
    {
        return factory($class, $times)->make($attributes);
    }

    /**
     * @param        $class
     * @param  array $attributes
     * @param  null  $times
     * @return mixed
     */
    protected function create($class, $attributes = [], $times = null)
    {
        return factory($class, $times)->create($attributes);
    }
}
