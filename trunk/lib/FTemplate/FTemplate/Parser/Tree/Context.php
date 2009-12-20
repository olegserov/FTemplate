<?php
class FTemplate_Parser_Tree_Context
{
    protected $_name;
    protected $_skel;
    protected $_parser;

    protected $_args;
    protected $_localVars;
    protected $_stack;
    protected $_tree;

    public function __construct(FTemplate_Parser $parser, FTemplate_Template_Skel $skel, $name)
    {
        $this->_parser = $parser;
        $this->_skel = $skel;
        $this->_name = $name;
    }

    public function push(FTemplate_Tag_Interface $tag)
    {
        if ($tag instanceof FTemplate_Tag_ICustom) {
            $this->_handleCustomTag($tag);
        }
        $this->_tree[] = $tag;
    }

    public function getTree()
    {
        return $this->_tree;
    }

    protected function _handleCustomTag(FTemplate_Tag_ICustom $tag)
    {
        $tag->parse($this);
    }

    /**
     * Gets skel
     * @return FTemplate_Template_Skel
     */
    public function getSkel()
    {
        return $this->_skel;
    }

    /**
     * Gets parser
     * @return FTemplate_Parser
     */
    public function getParser()
    {
        return $this->_parser;
    }
}