<?php

namespace WASP\Router;

/** Parses a URI path and turns it into an array of directories. (i.e. /questions/create/10?foo=bar -> ["questions", "create", "10"])
  * Ignores query parameters. */
function path_parse(string $uri) : array {
    
    $string = explode('?', $uri)[0];
    $components = explode('/', $string);

    $components = array_values(array_filter($components, function ($component) {
        return $component != '';
    }));

    return $components;

}

class MatchResult {
    public array $params;
}

/**
 * Matches a provided path-array to a series of matches. Returns either a `MatchResult` or `null` if no match was found.
 */
function path_match(array $uri, array $route) : MatchResult | null {

    if (count($uri) != count($route)) {
        return null;
    }
        
    $params = [];

    for ($i=0; $i < count($uri); $i++) { 
        if ( substr($route[$i], 0, 1) == '@') {
            $params[ substr($route[$i], 1) ] = $uri[$i];
            continue;
        }

        if ($uri[$i] != $route[$i]) {
            return null;
        }
            
    }

    $res = new MatchResult;
    $res->params = $params;

    return $res;

}