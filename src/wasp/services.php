<?php

namespace WASP\Services;

use WASP\ServiceAPI\Request;

use function WASP\Router\path_parse;
use function WASP\Util\ieoue;

/**
 *  -- Service Configuration --
 */

class Route {
    public array $route_parts;
    public string $calls;
    public array $accepts;
    public string $service;
}

class Service {
    public string $id;
    public string $namespace;
}

class ServicesConfiguration {
    public array $routes = [];
    public array $services = [];
}

function map_raw_route_to_Route(string $route_uri, array $route_info) : Route | null {

    $route = new Route;

    if (!isset($route_info['service'], $route_info['calls']))
        return null;

    $route->calls = $route_info['calls'];
    $route->service = $route_info['service'];
    $route->accepts = ieoue($route_info, 'accepts', ['GET', 'POST', 'PUT', 'DELETE']);
    $route->route_parts = path_parse($route_uri);

    return $route;

}

function map_raw_service_to_Service(string $id, array $service_info) : Service | null {

    $service = new Service;

    $service->id = $id;

    $service->namespace = ieoue($service_info, 'namespace', '');

    return $service;

}

function load_service_config(array $config) : ServicesConfiguration | null {

    // Services.json schema validation
    if (!isset($config['services']) || !is_array($config['services'])) return null;

    $services_object = $config['services'];

    $config = new ServicesConfiguration;

    if (isset($services_object['routes'])) {
        foreach ($services_object['routes'] as $uri => $info) {
            array_push($config->routes, map_raw_route_to_Route($uri, $info));
        }

        // filter the nulls
        $config->routes = array_values(array_filter($config->routes, function ($r) {
            return !is_null($r);
        }));
    }

    if (isset($services_object['services'])) {
        foreach ($services_object['services'] as $id => $info) {
            array_push($config->services, map_raw_service_to_Service($id, $info));
        }

        // filter the nulls
        $config->services = array_values(array_filter($config->services, function ($r) {
            return !is_null($r);
        }));
    }

    // $config->services = isset($services_object['services']) ? $services_object['services'] : [];

    return $config;
}

function get_service(ServicesConfiguration $config, string $id) : Service | null {
    foreach ($config->services as $service) {
        if ($service->id == $id)
            return $service;
    }
    return null;
}

/**
 *  -- Service Runner --
 */
function run_service(string $id, string $calls, Request $req_api) : bool {

    $path_to_service = SERVICES . $id . '/' . $id . '.php';

    if (!file_exists($path_to_service))
        return false;

    # include the service
    include_once $path_to_service;

    // Check if the callable function exists
    if (!function_exists($calls))
        return false;

    $calls($req_api);

    return true;

}