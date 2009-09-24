<?php
class FTemplate_Expression_Constant extends FTemplate_Expression_Base
{

    public function getRegExp()
    {
        return '\d+(\.\d+){0,1}';
    }

    public function parse(array $matches)
    {
        return $matches[0];
    }

}