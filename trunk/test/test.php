<?php
$res1 = preg_split(
    '{((a|b)|c)}six',
    '--a--b--c--',
    0,
    PREG_SPLIT_DELIM_CAPTURE
);
var_export($res1);