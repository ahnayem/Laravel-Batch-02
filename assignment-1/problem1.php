<?php

$number_arr = explode(' ', readline());

$number_arr = array_map('abs', $number_arr);
$lowest     = min($number_arr);

echo $lowest;