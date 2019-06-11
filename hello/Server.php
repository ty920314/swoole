<?php
namespace hello;
use swoole_server;
class Server
{
    private $serv;
    
    public function __construct()
    {
        $this->serv = new swoole_server("0.0.0.0",9501);
        /**
         * 设置运行时参数
         *
         * swoole_server->set函数用于设置swoole_server运行时的各项参数。服务器启动后通过$serv->setting来访问set函数设置的参数数组。
         *
         * @param array $setting
         */
        $this->serv->set([
            'worker_num' => 8,
            'deamonize' => FALSE
        ]);
        
        $this->serv->on('Start',[$this, 'onStart']);
        $this->serv->on('Connect',[$this, 'onConnect']);
        $this->serv->on('Receive',[$this, 'onReceive']);
        $this->serv->on('Close',[$this, 'onClose']);
        /**
         * 启动server，监听所有TCP/UDP端口
         * 启动成功后会创建worker_num+2个进程。主进程+Manager进程+worker_num个Worker进程
         *
         */
        $this->serv->start();
    }
    
    public function onStart( $serv)
    {
        echo "Start \n";
    }
    
    public function onConnect(swoole_server $serv,int $fd,int $from_id)
    {
        $serv->send($fd, "hello {$fd}!");
    }
    
    public function onReceive( swoole_server $serv, $fd, $data)
    {
        echo "get message from client {$fd}:{$data}\n";
        $serv->send($fd, $data);
    }
    
    public function onClose($serv, $fd, $from_id)
    {
        echo "Client {$fd} close connection\n";
    }
}
// 启动服务器 Start the server

$server = new Server();
