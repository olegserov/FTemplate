<?php
abstract class FTemplate_Tag_Base implements FTemplate_Tag_Interface
{
    protected $_input;

    public function __construct($input)
    {
        $this->_input = $input;
    }
}