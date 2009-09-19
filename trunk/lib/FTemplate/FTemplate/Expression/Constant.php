<?php
class FTemplate_Expression_Constant extends FTemplate_Expression
{

    public static function getRegExp()
    {
        return '[a-z_]\w+';
    }

    public function parse(array $matches)
    {
        return $matches[0];
    }

}