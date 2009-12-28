<?php
interface FTemplate_Expression_Interface
{
    public function getRegExp();

    public function compile(array $matches);
}