<?php
class FTemplate_Expression_Argument_RoundBracket implements FTemplate_Expression_Interface
{

    public function getRegExp()
    {
        return '\( (T_EXP) \)';
    }

    public function compile(array $matches)
    {
        return '(' . $matches[0] . ')';
    }

}