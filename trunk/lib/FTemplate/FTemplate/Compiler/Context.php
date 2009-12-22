<?php
class FTemplate_Compiler_Context
{
    protected $_skel;

    protected $_templates;

    protected $_currentNode;

    public function __construct($skel)
    {
        $this->_skel = $skel;
    }

    public function createTemplate($name)
    {
        if (isset($this->_templates[$name])) {
            $this->error("Template with name <$name> alredy exists");
        }

        $this->_currentNode = $this->_templates[$name] = new FTemplate_Compiler_Context_Template(
            $this,
            $name
        );

        return $this->_templates[$name];
    }

}