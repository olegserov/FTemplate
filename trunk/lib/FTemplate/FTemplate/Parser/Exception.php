<?php
class FTemplate_Parser_Exception extends FTemplate_Exception
{
    public function __construct($msg, FTemplate_Token_Base $token, FTemplate_Parser_Tree_Context $context)
    {
        $this->message = $msg . ' (' . $context->getSkel()->getClass() .')';
        $this->file = $context->getSkel()->getFile();
        $this->line = $token->getLine();
    }
}