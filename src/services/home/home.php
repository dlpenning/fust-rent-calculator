<?php

namespace FUST\Service\Home;

use WASP\ServiceAPI\Request;

function home(Request $req) {
    include VIEWS . 'home.php';
}