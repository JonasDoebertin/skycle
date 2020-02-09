<?php

namespace Tests\Concerns;

trait CreatesModels
{
    /**
     * @param        $class
     * @param null $state
     * @param array $attributes
     * @param null $times
     * @return mixed
     */
    protected function make($class, $state = null, $attributes = [], $times = null)
    {
        $factory = factory($class, $times);

        if ($state !== null) {
            $factory->state($state);
        }

        return $factory->make($attributes);
    }

    /**
     * @param        $class
     * @param null $state
     * @param array $attributes
     * @param null $times
     * @return mixed
     */
    protected function create($class, $state = null, $attributes = [], $times = null)
    {
        $factory = factory($class, $times);

        if ($state !== null) {
            $factory->state($state);
        }

        return $factory->create($attributes);
    }
}
