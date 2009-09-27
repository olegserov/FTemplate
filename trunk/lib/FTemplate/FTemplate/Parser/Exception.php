<?php
class FTemplate_Parser_Exception extends FTemplate_Exception
{
    public function __construct($msg, $file = null, $line = null)
    {
        $this->message = $msg;
        $this->file = $file;
        $this->line = $line;
    }
}