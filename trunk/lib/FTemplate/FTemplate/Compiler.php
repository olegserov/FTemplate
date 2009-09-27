<?php
class FTemplate_Compiler
{
    protected $_skel;

    public function compile(FTemplate_Template_Skel $skel)
    {
        $this->_skel = $skel;


        $this->_addHeader($skel->getClass());

        foreach ($skel->tree as $method => $context) {
            $this->_addMethod($method, $context);
        }

        $this->_addFooter();


        $this->_skel = null;
    }

    protected function _addMethod($method, $context)
    {
        $this->_addBlock($method);

        foreach ($context->getTree() as $node) {
            $this->_skel->code .= $node->getCode();
        }

        $this->_addEndBlock();
    }

    protected function _addHeader($className)
    {
        $this->_skel->code .= 'class ' . $className . ' extends FTemplate_Template_Base {';
    }

    protected function _addBlock($name = 'main')
    {
        $this->_skel->code .= 'public function ' . $name . '(){';
    }

    protected function _addEndBlock()
    {
        $this->_skel->code .= '}';
    }

    protected function _addFooter()
    {
        $this->_skel->code .= '}';
    }
}