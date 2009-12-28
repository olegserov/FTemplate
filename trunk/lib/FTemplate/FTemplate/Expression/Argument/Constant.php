<?php
class FTemplate_Expression_Argument_Constant implements FTemplate_Expression_Interface
{
    public function getRegExp()
    {
        return '[a-zA-Z_]\w+';
    }

    public function compile(array $matches)
    {
        return $matches[0];
    }
}