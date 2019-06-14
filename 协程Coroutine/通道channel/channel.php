<?php

use Swoole\Coroutine\Channel;

$chan = new Channel(10);

go(function () use ($chan){
    for ($i=0;$i<100;$i++){
//        Co::sleep(1);
        $chan->push([
            'rand'=>rand(1000,9999),
            'index'=>$i
        ]);
    }
});

go(function()use ($chan){
    while (TRUE){
        $data = $chan->pop();
//        var_dump($data);
    }
});
print_r($chan->stats());