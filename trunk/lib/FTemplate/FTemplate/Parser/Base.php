<?php
class FTemplate_Parser_Base
{
    protected $_context;

    public function __construct(FTemplate_Parser $context)
    {
        $this->_context = $context;
    }


    protected function _makeRegex($str)
    {
        $dic = array(
            'TAG_OPEN' => '\\{',
            'TAG_CLOSE' => '\\}',
            'TAG_COMMENT_OPEN' => '\\{\\*',
            'TAG_COMMENT_CLOSE' => '\\*\\}',
            'TAG_LITERAL_OPEN' => '\\{%',
            'TAG_LITERAL_CLOSE' => '%\\}',
            'SOMETHING' => '.*?'
        );

        $str = strtr($str, $dic);
        return $str;
    }
}