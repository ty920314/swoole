<?php

$clients = [];

$server = new swoole_websocket_server('0.0.0.0',9501);

$server->on('open',function(swoole_websocket_server $server, swoole_http_request $request) use (&$clients){
    echo '连接到fd'.$request->fd;
    $clients[] = $request->fd;
    $server->push($request->fd,'hi welcome im');
});

/**
 * onMessage回调 收到消息的动作
 */
$server->on('message',function(swoole_websocket_server $server, swoole_websocket_frame $frame) use (&$clients){
    echo sprintf("收到%s消息%s", $frame->fd, $frame->data);
    $server->push($frame->fd, "hi i'm imserver");
    //广播
    foreach ($clients as $client){
        $server->push($client, $frame->data);
    }
});

/**
 * 关闭连接
 */
$server->on('close',function($server, $fd) use (&$clients){
    echo sprintf("fd%s退出im", $fd);
    unset($clients[array_search($fd,$clients,TRUE)]);
    foreach ($clients as $client){
        $server->push($client, $fd.'退出聊天');
    }
});

$server->start();