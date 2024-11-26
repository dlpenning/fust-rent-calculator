<?php

/**
 * Contains the API that services can use.
 */

namespace WASP\ServiceAPI;

use Error;

/**
 * Represents the request info provided by the router.
 */
class Request {

    /**
     * Route parameters. Use get_param() and has_param() to access this data.
     */
    public array $route_params = [];

    public function has_param(string $id) {
        return isset($this->route_params[$id]);
    }


    /**
     * Validates a list of required parameters, as specified in the scheme. 
     */
    public function param_schema(array $route_parameters) : bool {
        foreach ($route_parameters as $param) {
            if (!$this->has_param($param))
                return false;
        }
        return true;
    }

    /**
     * Gets a route parameter. If no parameter is found, throws an error. 
     * 
     * Use `has_param()` to check if a param exists. Use `param_schema()` to validate a set of parameters that should exists.
     */
    public function get_param(string $id) : string {
        if (!$this->has_param($id))
            throw new Error("Parameter '$id' does not exist within the route.");
        return $this->route_params[$id];
    }

}

?>