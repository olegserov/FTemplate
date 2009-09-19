<?php
abstract class FTemplate_Tag implements FTemplate_ITag
{
    protected $_input;

    public function __construct($input)
    {
        $this->_input = $input;
    }
}