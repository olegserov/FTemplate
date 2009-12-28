<?php
abstract class FTemplate_Template_Base
{
    protected $_env;

    public function __construct(FTemplate_Template_Environment $env)
    {
        $this->_env = $env;
        $this->_env->registerTemplates($this, $this->_getTemplates());
    }

    public function show($name)
    {
        $this->_env->showTemplate($name, null);
    }

    abstract protected function _getTemplates();
}