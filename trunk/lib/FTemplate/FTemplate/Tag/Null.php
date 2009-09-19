<?php
class FTemplate_Tag_Null extends FTemplate_Tag
{
    public function getCode()
    {
        return str_repeat(substr_count($this->_input, "\n"), "\n");;
    }
}