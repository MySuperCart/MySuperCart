<?php

function authorizationRequired() {
    // throw new Exception("unauthorized");
    header("HTTP/1.0 401 Unauthorized");
    echo json_encode(array('error' => true, 'message' => "unauthorized"), JSON_PRETTY_PRINT); 
    exit;
}

function requireFields($arr) {
    global $_PHP_INPUT;
    for ($i=0; $i < count($arr); $i++) { 
        if(!isset($_PHP_INPUT[$arr[$i]])) {
            throw new Exception("missing_".$arr[$i]);
        }
    }
    return true;
}


?>