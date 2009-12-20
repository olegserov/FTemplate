<?php
class FTemplate_Parser_Base
{
    protected $_parser;

    public function __construct(FTemplate_Parser $parser)
    {
        $this->_parser = $parser;
    }

    protected function _makeRegex($str)
    {
        $dic = array(
            'TAG_OPEN' => '\\{',
            'TAG_CLOSE' => '\\}\n?',
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