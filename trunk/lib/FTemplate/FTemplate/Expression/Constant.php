<?php
class FTemplate_Expression_Constant extends FTemplate_Expression_Base
{

    public static function getRegExp()
    {
        return '(?!\w) [a-zA-Z_]\w+ (?!\w)';
    }

    public function parse(array $matches)
    {
        return $matches[0];
    }

}