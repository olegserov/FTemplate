<?php
class FTemplate_Parser_Tree extends FTemplate_Parser_Base
{
    protected $_skel;
    protected $_offset;
    protected $_total;


    public function get(FTemplate_Template_Skel $skel)
    {
        $this->_offset = 0;
        $this->_total = count($skel->tokens);
        $this->_skel = $skel;

        $this->_compileTree('main');
    }

    protected function _initContext($name)
    {
        if (isset($this->_skel->tree[$name])) {
            throw new Exception('Context already defined');
        }
        return $this->_skel->tree[$name] = new FTemplate_Parser_Tree_Context($name);
    }

    protected function _compileTree($name)
    {
        $context = $this->_initContext($name);

        for (;$this->_offset < $this->_total; $this->_offset++) {
            try {
                $context->push($this->_skel->tokens[$this->_offset]);
            } catch (FTemplate_Praser_Tree_SwitchContextException $e) {
                $this->_compileTree($e->getName());
            }
        }
    }
}