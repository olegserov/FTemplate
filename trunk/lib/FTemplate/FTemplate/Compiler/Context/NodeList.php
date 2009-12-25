<?php
class FTemplate_Compiler_Context_NodeList extends FTemplate_Compiler_Context_Node
{
    protected $_nodes = array();

    public function appendNode(FTemplate_Compiler_Context_Node $node)
    {
        $this->_nodes[] = $node;
        $node->setParent($this);
    }

    public function echoRaw()
    {
        foreach ($this->_nodes as $node) {
            $node->echoRaw();
        }
    }
}