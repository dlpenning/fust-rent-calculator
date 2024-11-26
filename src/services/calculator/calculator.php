<?php

namespace FUST\Service\Calculator;

use WASP\ServiceAPI\Request;

use function WASP\Errors\do404die;
use function WASP\Errors\do500die;

// /question/@calculator/@questionid
function question(Request $req) {
    
}

// /calculator/@calculator
function init_calculator(Request $req) {

    // validate the required param
    if (!$req->param_schema(['calculator']))
        do500die("Missing required parameter");

    // Point of this end point:
    // a) Check if the calculator exists
    // b) Setup the required data structure in the session of the user
    // c) Redirect to the desired first question of the calculator
    


}