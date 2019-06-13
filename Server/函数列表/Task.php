<?php
## 此功能用于将慢速的任务异步地去执行，比如一个聊天室服务器，可以用它来进行发送广播。当任务完成时，在task进程中调用$serv->finish("finish")告诉worker进程此任务已完成。当然swoole_server->finish是可选的。

$serv = new swoole_server("127.0.0.1", 9501, SWOOLE_BASE);

$serv->set(array(
    'worker_num'=>2,
    'task_worker_num'=>4
));

$serv->on('Receive',function(swoole_server $serv, $fd, $from_id, $data){
    echo '接收数据'.$data.PHP_EOL;
    $data = trim($data);
    $task_id = $serv->task($data, 0);
    $serv->send($fd, '分发任务,任务id为'.$task_id);
});
$a = 0;
$serv->on('Task',function (swoole_server $serv, $task_id, $from_id, $data) use (&$a){
    echo 'Tasker进程接收到数据'.PHP_EOL;
    echo "#{$serv->worker_id}\tonTask: [PID={$serv->worker_pid}]: task_id=$task_id, data_len=".strlen($data).".".PHP_EOL;
    $a++;
    print_r($a);
    $serv->finish($data);
});

$serv->on('Finish',function(swoole_server $serv, $task_id, $data) use (&$a){
    echo "Task#$task_id finished, data_len=".strlen($data).PHP_EOL;
});

$serv->on("WorkerStart",function ($serv,$worker_id){
    global $argv;
    echo $argv[0].PHP_EOL;
});

$serv->start();