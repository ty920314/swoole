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
]);
/**
 * 注册Server的事件回调函数。
 * 重复调用会覆盖上次的设置
 */
$serv->on('connect',function(\Swoole\Server $serv, $fd){
    echo 'Client:连接';
});

$serv->on('receive','onReceive');
$serv->on('close',function($serv, $fd){
    echo '连接关闭';
});
//每1秒执行
function onReceive(\Swoole\Server $server, $fd, $reactor_id, $data){
    $server->send($fd, "收到client消息");
    $server->tick(1000, function ()use($server,$fd){
        if($server->exist($fd)){
            //send具有原子性 多个进程调用send向同一个tcp连接发送数据,不会造成数据混杂
            $server->send($fd, 'hello tick test');
        }
    });
//    $server->after(3000, function ()use($server,$fd){
//        if($server->exist($fd)){
//            $server->send($fd, 'hello tick_after test');
//        }
//    });
    
}
$serv->start();