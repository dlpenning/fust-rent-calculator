<?php

namespace WASP\Errors;

function do404die() {
    include VIEWS . '404.php';
    die ();
}

function do500die(string $reason) {
    $reason = $reason;
    include VIEWS . '500.php';
    die ();
}