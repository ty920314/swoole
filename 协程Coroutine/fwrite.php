<?php
$fp = fopen(__DIR__ . "/test.txt", "a+");
function r($fp){
    go(function () use ($fp)
    {
        $r =  co::fwrite($fp, "hello world\n");
        Co::sleep(2);
        var_dump($r);
    });
}

for ($i=0;$i<100;$i++){
    r($fp);
}