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
        $text = ">";

        if (count($this->_nodes) == 0) {
            return $text . "\n<no nodes>";
        }

        $replace = array(
            "/\n\s*\\d+/" => '$0  ',
           // "/\n/" => '$0 ',
            "/ (  )(\#[^#]+\n)/" => " $2",
            "/ (  )(\#[^#]+$)/" => " $2"
        );
        foreach ($this->_nodes as $node) {
            $text .= "\n" . preg_replace(
                    array_keys($replace),
                    array_values($replace),
                    $node->__toString()
                );
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