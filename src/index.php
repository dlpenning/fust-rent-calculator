<?php

use WASP\ServiceAPI\Request;

use function WASP\Errors\do404die;
use function WASP\Errors\do500die;
use function WASP\Router\path_match;
use function WASP\Router\path_parse;
use function WASP\Services\get_service;
use function WASP\Services\load_service_config;
use function WASP\Services\run_service;

session_start();

/**
 * WASP Service Framework
 * 
 * @version 0.1.0
 * @author Daan Penning
 * 
 * @license MIT
 */


/* Constants */
define("ROOT", dirname(__FILE__) . '/');
define("WASP_LIB", ROOT . 'wasp/');
define("SERVICES", ROOT . 'services/');
define("VIEWS", ROOT . 'view/');

/* Include Statements */
include WASP_LIB . 'util.php';
include WASP_LIB . 'errors.php';
include WASP_LIB . 'router.php';
include WASP_LIB . 'services.php';
include WASP_LIB . 'service_api.php';

$config = load_service_config(json_decode( file_get_contents(ROOT . 'services/services.json'), true));

// The request will now be handled.
if (is_null($config))
    do500die("Configuration error: there has been a misconfiguration in services.json. Please contact a developer.");

// Routing time.

// Parse the uri
$parsed_uri = path_parse($_SERVER['REQUEST_URI']);

$res = null;

foreach ($config->routes as $route) {

    if ( !in_array( $_SERVER['REQUEST_METHOD'],$route->accepts) )
        continue;

    $res = path_match($parsed_uri, $route->route_parts);

    if (!is_null($res))
    {
        break;
    }

}

if (is_null($res)) {
    do404die();
}

// Check if the service exists.
$service = get_service($config, $route->service);

if (is_null($service))
    do500die("Configuration error: service " . $route->service . " is not defined.");

// Form the Request API object.
$request = new Request;
$request->route_params = $res->params;

$result = run_service($route->service, $service->namespace . $route->calls, $request);

if (!$result)
    do500die("Error: unable to call service (id: $route->service)");

?>