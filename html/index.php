<?php
/**
 * Author: yorks
 * Date: 02.04.2020
 */

require ('HTML.php');

$items = [
    1 => 'One',
    2 => 'Two',
    3 => 'Three',
    4 => 'Four',
];

echo HTML::selectBox('test', 2, $items);


