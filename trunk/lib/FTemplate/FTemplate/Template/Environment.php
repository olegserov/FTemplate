<?php
class FTemplate_Template_Environment
{
    public $vars;

    protected $_templates = array();

    public function __construct($vars)
    {
        $this->vars = $vars;
    }

    public function registerTemplates(FTemplate_Template_Base $base, array $templates)
    {
        foreach ($templates as $name => $quotedName) {
            $this->_templates[$name] = array($base, $quotedName);
        }
    }

    public function showTemplate($name, $args)
    {
        //@todo refactor this!
        if ($name == 'main') {
            if ($args !== null) {
                throw new Exception('Main template can not have arguments!');
            }
            if (!isset($this->_templates[$name])) {
                $name = FTemplate_Compiler_Context::TEMPLATE_GLOBAL_NAME;
            }
        }
        if (!isset($this->_templates[$name])) {
            //@todo refactor this!
            throw new Exception("Error template <$name> not found");
        }

        list($ob, $method) = $this->_templates[$name];

        if (!method_exists($ob, $method)) {
            throw new Exception('Call to undefied method: '. $method);
        }
        $ob->$method($args);
    }
}