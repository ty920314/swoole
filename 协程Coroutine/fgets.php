<?php
$fp = fopen(__DIR__."/readme.md", "r");
for($i=0;$i<100;$i++){
    go(function () use ($fp)
    {
        $r =  co::fgets($fp);
        var_dump($r);
    });
}
