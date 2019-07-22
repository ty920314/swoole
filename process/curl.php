<?php
$urls = [
    'http://www.baidu.com',
    'http://mi.com',
    'http://sina.com.cn',
    'http://www.gloole.com',
];
$works = [];
for ($i = 0; $i<count($urls);$i++){
    $process = new swoole_process(function(swoole_process $worker) use($i,$urls){
        $content = curlData($urls[$i]);
        $worker->write($content);
    }, TRUE);
    $pid = $process->start();
    $works[$pid] = $process;
}
//获取管道内容
foreach ($works as $pid=>$v){
    echo $pid.'->'.$v->read();
}
function curlData($url){
    sleep(1);
    return $url.'content'.PHP_EOL;
}