<?php
class FTemplate_Expression_RoundBracket extends FTemplate_Expression_Base
{

    public function getRegExp()
    {
        return '\( (T_EXP) \)';
    }

    public function parse(array $matches)
    {
        return '(' . $matches[0] . ')';
    }

}