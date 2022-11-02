<?php

function cleanString($string) {
    return filter_var(htmlspecialchars(trim($string), FILTER_SANITIZE_STRING));
}

function codeId($id) {
    return hexdec($id * 1000);
}

function decodeId($id) {
    return dechex($id) / 1000;
}



