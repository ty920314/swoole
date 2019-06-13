<?php

$serv = new \Swoole\Server('127.0.0.1', 9501);
$serv->set([
    'worker' => 4,
]);
/**
 * 注册Server的事件回调函数。
 * 重复调用会覆盖上次的设置
 */
$serv->on('connect', function (\Swoole\Server $serv, $fd) {
    echo 'Client:连接';
});

$serv->on('receive', 'onReceive');

$serv->on('close', function ($serv, $fd) {
    echo '连接关闭';
});
//每1秒执行
function onReceive(\Swoole\Server $server, $fd, $reactor_id, $data)
{
    //大数据的话 可以将数据写入文件 通过sendfile发送
    //sendfile函数调用操作系统提供的sendfile系统调用，由操作系统直接读取文件并写入socket
    //因此sendfile只有2次内存拷贝，使用此函数可以降低发送大量文件时操作系统的CPU和内存占用
    $server->sendfile($fd,'../test.txt');
}

$serv->start();