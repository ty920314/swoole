<?php


use Swoole\Coroutine\Channel as Channel;
use Swoole\Coroutine\MySQL;

class MysqlPool
{
    /**
     * channel基于引用计数实现，是零拷贝的
     * @var Channel
     */
    public $pool;
    /**
     * 连接池尺寸
     * @var int
     */
    private $poolSize;
    
    private static $instance;
    
    public static function getMysqlPool($config = []){
        if(!empty(self::$instance)){
            return self::$instance;
        }
        self::$instance = new self($config);
        return self::$instance;
    }
    /**
     * MysqlPool constructor.
     * @param     $config
     *    config.mysql = [
     *               'host' => '127.0.0.1',
     *               'port' => 3306,
     *               'user' => 'user',
     *               'password' => 'pass',
     *               'database' => 'test',
     *               ]
     */
    private function __construct($config = [])
    {
        if(empty($config)){
            throw new RuntimeException("empty config!");
        }
        $this->poolSize = !empty($config['poolSize'])?$config['poolSize']:100;
        $this->pool = new Swoole\Coroutine\Channel($this->poolSize);
        
        for ($i = 0; $i < $this->poolSize; $i++)
        {
            co::create(function() use ($config){
                $swoole_mysql = new Swoole\Coroutine\MySQL();
                $db = $swoole_mysql->connect($config['mysql']);
                if ($db == false)
                {
                    throw new RuntimeException("failed to connect redis server.");
                }
                else
                {
                    $this->push($swoole_mysql);
                }
            });
        }
        
    }
    
    public function isFinish(){
        if($this->pool->stats()['queue_num']+1 == $this->poolSize){
            return TRUE;
        }
        return FALSE;
    }
    /**
     * 多个生产者协程同时push时，底层自动进行排队，底层会按照顺序逐个resume这些生产者协程
     * @param $db
     */
    public function push($db)
    {
        $this->pool->push($db);
    }
    
    /**
     * 多个消费者协程同时pop时，底层自动进行排队，按照顺序逐个resume这些消费者协程
     * @return Swoole\Coroutine\Mysql
     */
    public function getDb()
    {
        $mysql = $this->pool->pop();
        defer(function () use ($mysql){ //释放
            $this->push($mysql);
        });
        return $mysql;
    }
    
}


$pool = MysqlPool::getMysqlPool([
    'mysql'=>[
        'host' => '127.0.0.1',
        'port' => 3306,
        'user' => 'root',
        'password' => '123456',
        'database' => 'test',
    ],
    'poolSize'=>3
]);
for ($i=1;$i<=20;$i++){
    co::create(function() use ($pool,$i){
        $db = $pool->getDb();
        defer(function ()use ($pool){
            if($pool->isFinish()){
                echo 'task finish ok';
            }
        });
        print_r($db->query("select * from `yilu_selfupload_videos` where `id`={$i}"));
        
    });
}

