<?php
swoole_async_writefile(__DIR__."/write.txt","content_test",function(){
    //todo 日志
    echo 'write ok'.PHP_EOL;
},FILE_APPEND);//追加

echo 'start'.PHP_EOL;