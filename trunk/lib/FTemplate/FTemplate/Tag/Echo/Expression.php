<?php
class FTemplate_Tag_Echo_Expression extends FTemplate_Tag_Base implements FTemplate_Tag_ICustom
{
    protected $_code;

    public static function getRegExp()
    {
        return '/.*/s';
    }

    public function getCode()
    {
        return 'echo ' . $this->_code . ';';
    }

    public function parse(FTemplate_Parser_Tree_Context $context)
    {
        $this->_code = $context->getParser()
            ->getExpressionParser()
            ->parse($this->_token, $context);
    }
}