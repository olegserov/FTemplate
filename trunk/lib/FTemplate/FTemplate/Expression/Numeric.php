<?php
class FTemplate_Expression_Numeric extends FTemplate_Expression_Base
{

    public function getRegExp()
    {
        return ' \d+(\.\d+)? (?![\d\~])';
    }

    public function parse(array $matches)
    {
        return $matches[0];
    }

}