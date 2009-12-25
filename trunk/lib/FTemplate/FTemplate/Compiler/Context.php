<?php
class FTemplate_Compiler_Context
{
    protected $_skel;

    protected $_templates;

    protected $_globalTemplate;

    protected $_currentTemplate;

    const TEMPLATE_GLOBAL_NAME = '__global__';

    public function __construct($skel)
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

    public function createNode()
    {
        return new FTemplate_Compiler_Context_Node($this);
    }

    public function appendNode($node)
    {
        $this->_currentTemplate->appendNode($node);
    }

    public function error($msg)
    {
        echo $msg;
    }
}