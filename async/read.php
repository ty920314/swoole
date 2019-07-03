<?php
swoole_async_readfile(__DIR__."/write.txt",function($filename, $content){
    
    echo 'name:'.$filename.PHP_EOL;
    echo 'content:'.$content.PHP_EOL;
});

echo 'start!'.PHP_EOL;