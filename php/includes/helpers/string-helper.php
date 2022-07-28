<?php
// Function for basic field validation (present and neither empty nor only white space
function IsNullOrEmptyString($str){
    return ($str === null || trim($str) === '');
}

function TestString(){
    return "Test!";
}

function TrimHtmlBrTag($html) {
    return trim(preg_replace('/^(<br\s*\/?>)*|(<br\s*\/?>)*$/i', '', $html));
}

?>