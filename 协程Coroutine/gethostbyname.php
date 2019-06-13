<?php

go(function(){
    ## 将域名解析为IP，基于同步的线程池模拟实现。底层自动进行协程调度。
    $ip = Co::gethostbyname("www.baidu.com", AF_INET);
    print_r($ip);
    ## 进行DNS解析，查询域名对应的IP地址，与gethostbyname不同，getaddrinfo支持更多参数设置，而且会返回多个IP结果。
    $array = co::getaddrinfo("www.baidu.com");
    print_r($array);
});
$filename = __DIR__ . "/test.txt";
## 协程方式读取文件。
go(function () use ($filename)
{
    $r =  co::readFile($filename);
    var_dump($r);
});