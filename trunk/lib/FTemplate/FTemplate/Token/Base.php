<?php
abstract class FTemplate_Token_Base
{
    protected $_input;

    protected $_line;

    public function __construct($input, $line)
    {
        $this->_input = $input;
        $this->_line = $line;
    }

    public function getInput()
    {
        return $this->_input;
    }

    public function getLine()
    {
        return $this->_line;
    }
}