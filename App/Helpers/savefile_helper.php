<?php


function savefile($file, $info)
{
    $file = fopen($file, "w");
    fwrite($file, $info);
    fclose($file);
}
