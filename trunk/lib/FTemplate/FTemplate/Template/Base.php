<?php
class FTemplate_Template_Base
{
    protected $_vars;

    public function __construct($vars)
    {
        $this->_vars = $vars;
    }

    public function show($name)
    {
        echo $name;
    }
}