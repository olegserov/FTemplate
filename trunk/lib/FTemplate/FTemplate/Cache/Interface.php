<?php
interface FTemplate_Cache_Interface
{
    public function load($name, $time);

    public function save($name, $time, $code);
}