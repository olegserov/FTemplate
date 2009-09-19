<?php
interface FTemplate_ICache
{
    public function load($name, $time);
    public function save($name, $time, $code);
}