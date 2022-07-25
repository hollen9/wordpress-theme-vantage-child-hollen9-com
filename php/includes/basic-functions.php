<?php
// Function for basic field validation (present and neither empty nor only white space
function IsNullOrEmptyString($str){
    return ($str === null || trim($str) === '');
}


?>