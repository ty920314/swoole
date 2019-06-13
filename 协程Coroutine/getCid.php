<?php
echo Co::getCid();//-1 不在协程环境内返回-1

go(function(){
    echo 'test getCid';
    echo Co::getCid();//1
});

go(function(){
    echo 'test getCid';
    echo Co::getCid();//2
});

