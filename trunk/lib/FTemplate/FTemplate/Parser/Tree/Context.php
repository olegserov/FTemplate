<?php
class FTemplate_Parser_Tree_Context
{
    protected $_args;
    protected $_name;
    protected $_localVars;
    protected $_stack;
    protected $_tree;

    public function __construct($name)
    {
        $this->_name = $name;
    }

    public function push(FTemplate_Tag_Interface $tag)
    {
        if ($tag instanceof FTemplate_Tag_ICustom) {
            $this->_handleCustomTag($tag);
            $this->_tree[] = $tag;
        } else {
            $this->_tree[] = $tag;
        }
    }

    public function getTree()
    {
        return $this->_tree;
    }

    protected function _handleCustomTag($tag)
    {
        $tag->parse($this);

    }
}