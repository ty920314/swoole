<?php
class Client{
    /**
     * @var swoole_client
     */
    private $client;
    
    public function __construct()
    {
        $this->client = new swoole_client(SWOOLE_SOCK_TCP);
    }
    
    public function connect()
    {
        if(!$this->client->connect("127.0.0.1", 9501, 1)){
            echo "Error: {$this->client->errCode}\n";
        }
        
        fwrite(STDOUT,"请输入消息");
        $msg = trim(fgets(STDIN));
        $this->client->send($msg);
        
        $message = $this->client->recv();
        echo "get message from server:{$message}";
    }
    
}
$client = new Client();
$client->connect();