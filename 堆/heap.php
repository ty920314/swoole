<?php

//最大堆

class MaxIntHeap extends SplHeap{
    protected function compare($value1, $value2)
    {
        return $value1-$value2;//对int类型比较
        
    }
}
class MinHeap extends SplHeap{
    protected function compare($value1, $value2)
    {
        return $value2-$value1;
    }
}

class MaxHeap2 extends SplMaxHeap{

}
class MinHeap2 extends SplMinHeap{

}

$list = new MaxIntHeap;
$list->insert(45);
$list->insert(12);
$list->insert(65);

//foreach ($list as $k=>$v){
//    print_r($k);
//    print_r($v);
//}


class MyHeap extends SplHeap
{
    protected function compare($a, $b)
    {
        return $a->value - $b->value;
    }
}
class MyObject
{
    public $value;
    
    function __construct($value)
    {
        $this->value = $value;
    }
}

$list = new MyHeap;
$list->insert(new MyObject(12));
$list->insert(new MyObject(98));
foreach ($list as $v){
    print_r($v->value);
}