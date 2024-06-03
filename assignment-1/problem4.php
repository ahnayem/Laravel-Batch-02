<?php

$rows = (int)readline();

for ($i = 1; $i <= $rows; $i++) {
    echo str_repeat(' ', $rows - $i);
    echo str_repeat('*', (2 * $i) - 1).PHP_EOL;
}

