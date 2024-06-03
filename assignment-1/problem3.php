<?php

$sentence = "I love programming";

$reversed_words = implode(' ', array_map('strrev', explode(' ', $sentence)));

echo $reversed_words;