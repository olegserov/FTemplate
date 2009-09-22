<?php
class FTemplate_Parser_Chunks extends FTemplate_Parser_Base
{
    public function get($content)
    {
        return preg_split($this->_makeRegex('/(
                  TAG_OPEN SOMETHING TAG_CLOSE
               |  TAG_COMMENT_OPEN SOMETHING TAG_COMMENT_CLOSE
               |  TAG_LITERAL_OPEN SOMETHING TAG_LITERAL_CLOSE
            )/sx'),
            $content,
            0,
            PREG_SPLIT_DELIM_CAPTURE
        );
    }
}