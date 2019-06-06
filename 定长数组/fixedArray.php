<?php
# 和普通php数组不同  定长数组读写更好

$array = new SplFixedArray(5);
$array[1] = 2;
$array->setSize(8);
print_r($array);