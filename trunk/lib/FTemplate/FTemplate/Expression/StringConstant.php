<?php
class FTemplate_Expression_StringConstant extends FTemplate_Expression_Base
{

    public static function getRegExp()
    {
        return "'(([^']|\\'|\\\\)*)'";
    }

    public function parse(array $matches)
    {
        return "'" . $matches[1] . "'";
    }

}