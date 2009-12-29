<?php
class FTemplate_Compiler_Context_NodeList extends FTemplate_Compiler_Context_Node
{
    protected $_nodes = array();

    public function appendNode(FTemplate_Compiler_Context_Node $node)
    {
        $node->setParent($this, array_push($this->_nodes, $node) - 1);
    }

    public function echoRaw()
    {
        foreach ($this->_nodes as $node) {
            $node->echoRaw();
        }
    }

    public function __toString()
    {
        $text = "NodeList\n";

        foreach ($this->_nodes as $i => $node) {
            $text .= "#$i  "
                . str_replace("\n", "\n#$i  ", $node->__toString())
                . "\n";
        }

        return $text;
    }

    public function get($index)
    {
        if (!isset($this->_nodes[$index])) {
            $this->_context->error(
                'Call to undefined index %s',
                $index
            );
        }
        return $this->_nodes[$index];
    }
}