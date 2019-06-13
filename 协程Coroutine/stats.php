<?php
$a = 1;
function test(){
    global $a;
    echo $a.PHP_EOL;
    $a++;
}


for ($i=1;$i<3000;$i++){
    go('test');
}

var_dump(Co::stats());
//array(3) {
//  ["c_stack_size"]=>
//  int(2097152)
//  ["coroutine_num"]=> 当前协程数
//  int(2999)
//  ["coroutine_peak_num"]=> 当前运行的协程数量的峰值
//  int(2999)
//}