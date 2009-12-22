<?php
class FTemplate_Compiler_Context_Node
{
    protected $_context;

    protected $_parent;

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

    public function setChunk($chunk, $line)
    {

    }
}