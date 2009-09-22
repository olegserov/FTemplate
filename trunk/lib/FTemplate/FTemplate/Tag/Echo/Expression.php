<?php
class FTemplate_Tag_Echo_Expression extends FTemplate_Tag_Base implements FTemplate_Tag_ICustom
{
    public static function getRegExp()
    {
        return '.*';
    }

    public function getCode()
    {
        return sprintf("echo %s;", $this->_input);
    }

    public function parse($context)
    {
        $exp = new FTemplate_Expression;
        $this->_input = $exp->parse($this->_input, $context);
    }
}