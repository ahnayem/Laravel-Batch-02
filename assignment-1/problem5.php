<?php

$number_str = readline();

$number_arr = str_split($number_str);
$number_arr = array_map('intval', $number_arr);
$sum        = array_sum($number_arr);

echo $sum;