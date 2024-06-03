<?php

$words   = file('prob2.txt');

$word_count = 0;

foreach ($words as $word) {
    $word_count += str_word_count($word);
}

echo $word_count;

