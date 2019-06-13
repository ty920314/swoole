<?php
/**
 * construct
 */
$serv = new \Swoole\Server('127.0.0.1',9501);
/**
 * 用于设置运行时的各项参数。服务器启动后通过$serv->setting来访问Server->set方法设置的参数数组。
 */
$serv->set([
    'reactor'=>2,
    'worker'=>4,
//    'daemonize'=>1 //后台守护模式
]);
/**
 * 注册Server的事件回调函数。
 * 重复调用会覆盖上次的设置
 */
$serv->on('connect',function(\Swoole\Server $serv, $fd){
    echo 'Client:连接';
});


$serv->on('close',function($serv, $fd){
     echo '连接关闭';
});

//$serv->addListener('127.0.0.1',9555,SWOOLE_SOCK_TCP);
print_r($serv->ports);
//广播功能(群发)
$process = new \Swoole\Process(function($process) use($serv){
    while (TRUE){
        $msg = $process->read();
        foreach ($serv->connections as $conn){
            $serv->send($conn, $msg);
        }
    }
});
$serv->addProcess($process);
$serv->on('receive',function(\Swoole\Server $serv, $fd, $reactor_id, $data) use($process){
    //群发收到的消息
    $process->write($data);
    $serv->send($fd, 'Swoole:'.$data.'   id:'.$reactor_id);
});
$serv->start();