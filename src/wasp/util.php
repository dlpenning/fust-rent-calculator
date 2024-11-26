<?php

namespace WASP\Util;

use Error;

/**
 * If exists on, use. Else.
 */
function ieoue(array $parent, string $key, mixed $alternate) : mixed  {
    return isset($parent[$key]) ? $parent[$key] : $alternate;
}

abstract class Map {

    protected array $values = [];

    abstract function get($name);
    abstract function set($name, $value);

    protected function store($key, $value) {
        $this->values[$key] = $value;
    }

    protected function fetch($key) {
        if (isset($this->values[$key]))
            return $this->values[$key];
        else throw new Error("Invalid key access on map.");
    }

}