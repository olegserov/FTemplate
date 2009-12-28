<?php
class FTemplate_Expression_Argument_Numeric implements FTemplate_Expression_Interface
{

    public function getRegExp()
    {
        return ' \d+(\.\d+)? (?![\d\~])';
    }

    public function compile(array $matches)
    {
        return $matches[0];
    }

}