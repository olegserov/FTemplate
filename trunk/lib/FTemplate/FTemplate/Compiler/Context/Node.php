<?php
class FTemplate_Compiler_Context_Node
{
    protected $_context;

    protected $_parent;

    protected $_class;

    protected $_type;

    protected $_rawCode;

    protected $_chunk;

    protected $_line;

    protected $_body;

    public function __construct($context)
    {
        $this->_context = $context;
    }

    public function setParent($parent)
    {
        $this->_parent = $parent;
    }

    public function getParent()
    {
        return $this->_parent;
    }

    public function getContext()
    {
        return $this->_context;
    }

    public function setChunk($chunk, $line)
    {
        $this->_chunk = $chunk;
        $this->_line = $line;
    }

    public function setClass($class)
    {
        $this->_class = $class;
    }

    public function setType($type)
    {
        $this->_type = $type;
    }

    public function setBody($body)
    {
        $this->_body = $body;
    }

    public function getBody()
    {
        return $this->_body;
    }

    public function setRaw($raw)
    {
        $this->_rawCode = $raw;
    }

    public function echoRaw()
    {
        echo $this->_rawCode;
    }
}