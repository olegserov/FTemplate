<?php
class FTemplate_Factory
{
    /**
     * The compiler
     * @var FTemplate_Compiler
     */
    protected $_compiler;

    /**
     * The compiler
     * @var FTemplate_Parser
     */
    protected $_parser;

    /**
     * The compiler
     * @var FTemplate_Cache
     */
    protected $_cache;

    /**
     * The Expression parser && compiler
     * @var FTemplate_Expression
     */
    protected $_expression;

    /**
     * Gets expression
     * @return FTemplate_Expression
     */
    public function getExpression()
    {
        if (!$this->_expression) {
            $this->_initExpression();
        }

        return $this->_expression;
    }

    /**
     * Create compiler
     * @return void
     */
    protected function _initExpression()
    {
        $this->_expression = new FTemplate_Expression($this);
    }

    /**
     * Gets compiler
     * @return FTemplate_Compiler
     */
    public function getCompiler()
    {
        if (!$this->_compiler) {
            $this->_initCompiler();
        }

        return $this->_compiler;
    }

    /**
     * Create compiler
     * @return void
     */
    protected function _initCompiler()
    {
        $this->_compiler = new FTemplate_Compiler($this);
    }

    /**
     * Gets compiler
     * @return FTemplate_Parser
     */
    public function getParser()
    {
        if (!$this->_parser) {
            $this->_initParser();
        }

        return $this->_parser;
    }

    /**
     * Create parser
     * @return void
     */
    protected function _initParser()
    {
        $this->_parser = new FTemplate_Parser($this);
        $this->_parser->addTag(new FTemplate_Tag_Control_Comment(), 1);
        $this->_parser->addTag(new FTemplate_Tag_Block_Logic_If());
        $this->_parser->addTag(new FTemplate_Tag_Inline_Echo_Expression(), 100);
    }

    /**
     * Gets cache
     * @return FTemplate_Cache
     */
    public function getCache()
    {
        if (!$this->_cache) {
            $this->_initCache();
        }

        return $this->_cache;
    }

    /**
     * Create cache
     * @return void
     */
    protected function _initCache()
    {
        $this->_cache = new FTemplate_Cache($this);
    }

    public function createContext()
    {
        return new FTemplate_Compiler_Context($this);
    }

    public function createEnvironment($vars)
    {
        return new FTemplate_Template_Environment($vars);
    }
}
