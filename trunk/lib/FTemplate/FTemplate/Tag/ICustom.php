<?php
interface FTemplate_Tag_ICustom extends FTemplate_Tag_Interface
{
    public static function getRegEx();
    public function parse($context);
}