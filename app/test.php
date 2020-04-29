<?php

use Ds\Vector;

require __DIR__ . '/vendor/autoload.php';


return;
$initArray = [];
for ($i = 10; $i > 0; $i--) {
    $initArray[] = 0;
}
$v = new Vector($initArray);

var_dump($v);

$v->shift();
$v->push(1);
var_dump($v);


