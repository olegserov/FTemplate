<?php
require dirname(dirname(__FILE__)) . '/classes/Bootstrap.php';
Bootstrap::console();

$filter = '*';

$phpt = new PHPT();
$phpt->setPath(dirname(__FILE__) . '/functional/');
$phpt->setPath(dirname(__FILE__) . '/regress/');
$phpt->setFilter($filter);
$phpt->run();