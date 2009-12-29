<?php
class FTemplate_Compiler_Context extends FTemplate_Base
{
    protected $_skel;

    protected $_templates;

    protected $_globalTemplate;

    protected $_currentTemplate;

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

        $this->_currentTemplate = $this->_templates[$name];

        return $this->_templates[$name];
    }

    public function createNode($chunk, $line)
    {
        $this->_prevNode = $this->_lastNode;
        return $this->_lastNode = new FTemplate_Compiler_Context_Node($this, $chunk, $line);
    }

    public function appendNode($node)
    {
        $this->_currentTemplate->appendNode($node);
    }

    public function error($msg)
    {
        throw new Exception(sprintf(
            "Error Msg: %s;\nFile: %s:%d\nLast tag: %s; Prev node: %s",
            $msg,
            $this->_skel->getFile(),
            $this->_lastNode->getLine(),
            $this->_lastNode->getChunk(),
            (isset($this->_prevNode) ? $this->_prevNode->getChunk() : '<none>')
        ));
    }

}