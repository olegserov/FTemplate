<?php
class FTemplate_Expression_Constant extends FTemplate_Expression_Base
{

    public static function getRegExp()
    {
        return '(?![\d\.])\d+(\.\d+)?(?![\d.])';
    }

    public function parse(array $matches)
    {
        return $matches[0];
    }

}