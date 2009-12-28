<?php
class FTemplate_Expression_Argument_StringConstant implements FTemplate_Expression_Interface
{

    public function getRegExp()
    {
        return "'(([^'\\\\]*|\\\\.)*)'";
    }

    public function compile(array $matches)
    {
        return "'" . $matches[1] . "'";
    }

}