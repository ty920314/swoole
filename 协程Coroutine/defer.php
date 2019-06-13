<?php
// defer用于资源的释放, 会在协程关闭之前(即协程函数执行完毕时)进行调用, 就算抛出了异常, 已注册的defer也会被执行.

go(function(){
    defer(function(){
        echo 1;
    });
    defer(function(){
        echo 3;
    });
    throw new Exception('asd');
    echo 2;
});