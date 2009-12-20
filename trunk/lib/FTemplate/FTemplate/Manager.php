<?php
class FTemplate_Manager
{
    /**
     * The compiler
     * @var FTemplate_Compiler
     */
    protected $_compiler;

    /**
     * The compiler
     * @var FTemplate_Cache
     */
    protected $_cache;

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
        $this->_compiler = new FTemplate_Compiler();
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
        $this->_cache = new FTemplate_Cache();
    }
}
