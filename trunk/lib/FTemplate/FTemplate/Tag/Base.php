<?php
abstract class FTemplate_Tag_Base implements FTemplate_Tag_Interface
{
    protected $_token;
    protected $_key;

    public function __construct(FTemplate_Token_Base $token, $key = null)
    {
        $this->_key = $key;
        $this->_token = $token;
    }
}