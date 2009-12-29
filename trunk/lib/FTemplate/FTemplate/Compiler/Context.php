<?php
class FTemplate_Compiler_Context extends FTemplate_Base
{
    protected $_skel;

    protected $_templates;

    protected $_globalTemplate;

    protected $_currentNode;

    protected $_lastNode;
    protected $_prevNode;

    const TEMPLATE_GLOBAL_NAME = '__global__';

    public function setSkel($skel)
    {
        $this->_skel = $skel;

        $this->createTemplate(self::TEMPLATE_GLOBAL_NAME);
    }

    public function getTemplates()
    {
        return $this->_templates;
    }

    public function createTemplate($name)
    {
        if (isset($this->_templates[$name])) {
            $this->error("Template with name <$name> alredy exists");
        }

        $this->_templates[$name] = new FTemplate_Compiler_Context_Template(
            $this,
            $name
        );

        $this->_currentNode = $this->_templates[$name];

        return $this->_templates[$name];
    }

    public function createNode($chunk, $line)
    {
        $this->_prevNode = $this->_lastNode;
        return $this->_lastNode = new FTemplate_Compiler_Context_Node($this, $chunk, $line);
    }

    public function appendNode($node)
    {
        $this->_currentNode->appendNode($node);
    }

    public function error($msg)
    {
        $args = func_get_args();
        $msg = array_shift($args);

        $parent = $this->_currentNode;
        $i = 3;
        while ($parent->getParent() !== null && $i-- > 0) {
            $parent = $parent->getParent();
        }

        throw new Exception(sprintf(
            "Error Msg: %s;\n"
                . "File: %s:%d;\n"
                . "NodeTree:\n%s\n"
                . "%3s >>>>>>HERE!",
            vsprintf($msg, $args),
            $this->_skel->getFile(),
            $this->_lastNode->getLine(),
            (string) $parent,
            $this->_lastNode->getLine()
        ));
    }

    public function levelDown()
    {
        $this->appendNode(
            $list = new FTemplate_Compiler_Context_NodeList(
                $this,
                $this->_lastNode->getChunk(),
                $this->_lastNode->getLine()
            )
        );

        $this->_currentNode = $list;
    }

    public function levelUp(FTemplate_Compiler_Context_Node $node, array $allowed)
    {
        $parent = $this->_currentNode->getParent();

        if ($parent == null) {
            $this->error(
                'Unexpected close tag! Zero level!'
            );
        }

        $openTag = $parent->get(
            $this->_currentNode->getIndex() - 1
        );

        if ($openTag->getClass() !== $node->getClass()) {
            $this->error(
                'Closing tag mismatch! opened %s closing %s',
                get_class($openTag->getClass()),
                get_class($node->getClass())
            );
        }

        if (!in_array($openTag->getType(), $allowed)) {
            $this->error(
                'Closing tag mismatch! Open tag is %s; expected: %s;',
                $openTag->getType(),
                join(', ', $allowed)
            );
        }

        $this->_currentNode = $parent;

        return $openTag;
    }

}