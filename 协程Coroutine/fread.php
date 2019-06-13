<?php
$fp = fopen(__DIR__."/readme.md", "r");
co::create(function () use ($fp)
{
    $r =  co::fread($fp);
    var_dump($r);
});