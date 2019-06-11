<?php

$str = 'hi ';

$timer_id = swoole_timer_tick(1000, function($timer_id, $params) use ($str){
    echo $str.$params;//输出 hi ty
},'ty');

print_r($timer_id);
//swoole_timer_clear($timer_id);

swoole_timer_after(2000, function(){
    echo 'test after';
});

