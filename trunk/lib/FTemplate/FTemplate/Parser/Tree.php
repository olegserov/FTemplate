<?php
class FTemplate_Parser_Tree extends FTemplate_Parser_Base
{
    protected $_tree;
    protected $_offset;
    protected $_total;
    protected $_tags;

    public function get($tags)
    {
        $this->_offset = 0;
        $this->_total = count($tags);
        $this->_tags = $tags;
        $this->_tree = array();

        $this->_compileTree('main');

        return $this->_tree;
    }

    protected function _initContext($name)
    {
        if (isset($this->_tree[$name])) {
            throw new Exception('Context already defined');
        }
        return $this->_tree[$name] = new FTemplate_Parser_Tree_Context($name);
    }

    protected function _compileTree($name)
    {
        $context = $this->_initContext($name);

        for (;$this->_offset < $this->_total; $this->_offset++) {
            try {
                $context->push($this->_tags[$this->_offset]);
            } catch (FTemplate_Praser_Tree_SwitchContextException $e) {
                $this->_compileTree($e->getName());
            }
        }
    }
}