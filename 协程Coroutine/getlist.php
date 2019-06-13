<?php

use Swoole\Coroutine;

function testgetlist(){
    global $a;
    echo $a++;
    $coros = Coroutine::listCoroutines();
    foreach($coros as $cid)
    {
        var_dump(Coroutine::getBackTrace($cid));
    }
}


go('testgetlist');


