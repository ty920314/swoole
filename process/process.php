<?php
$process = new swoole_process(function(swoole_process $process){
//    echo 1;

},FALSE);

$pid = $process->start();
echo $pid . PHP_EOL;

swoole_process::wait();