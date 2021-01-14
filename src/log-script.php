<?php

function logToScript($mixed) {
    $dump = var_export($mixed, true);
    echo "<script defer>console.log(`$dump`)</script>";
}
