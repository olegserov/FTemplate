<?php
class FTemplate
{
    /**
     * Cache driver.
     *  * false - cache disabled;
     *  * null - cache not inited yet;
     * @var FTemplate_Cache_Interface
     */
    protected $_cacheDriver = null;

    protected $_vars = array();

    /**
     * The compiler
     * @var FTemplate_Compiler
     */
    protected $_compiler;

    /**
     * The parser
     * @var FTemplate_Parser
     */
    protected $_parser;

    public function assign($name, $value = null)
    {
        if (is_array($name)) {
            $this->_vars = array_merge($this->_vars, $name);
        } else {
            $this->_vars[$name] = $value;
        }
    }

    public function setCacheDriver($driver)
    {
        if ($driver == false) {
            $this->_cacheDriver = false;
        }

        if (!($driver instanceof FTemplate_Cache_Interface)) {
            throw new Exception(get_class($driver) . ' is not instance of FTemplate_Cache_Interface');
        }

        if (is_object($driver)) {
            $this->_cacheDriver = $driver;
        } elseif (!class_exists($driver)) {
            throw new Exception($driver . ' not found');
        } else {
            $this->_cacheDriver = new $driver;
        }
    }

    /**
     * Gets cache driver
     * @return FTemplate_Cache_Interface
     */
    public function getCacheDriver()
    {
        // Cache driver is not inited
        if ($this->_cacheDriver === null) {
            $this->_cacheDriver = new FTemplate_Cache_FS();
        }

        return $this->_cacheDriver;
    }

    protected function _loadFile($origFile)
    {

        $skel = new FTemplate_Template_Skel($origFile);

        // Loaded in php
        if (class_exists($skel->getClass(), false)) {
            return $skel;
        }

        if (
            $this->getCacheDriver()
            && $this->getCacheDriver()->load($skel)
        ) {
            return $skel;
        }

        $this->_compile($skel);

        //Caching is allowed
        if ($this->getCacheDriver()) {
            $this->getCacheDriver()->save($skel);
        }

        eval($skel->getCode());

        return $skel;
    }

    public function display($origFile)
    {
        $skel = $this->_loadFile($origFile);
        $this->_call($skel);
    }

    protected function _call(FTemplate_Template_Skel $skel, $method = 'main')
    {
        $class = $skel->getClass();
        $ob = new $class($this->_vars);
        $ob->$method();
    }

    /**
     * Gets parser
     * @return FTemplate_Parser
     */
    public function getParser()
    {
        if (!$this->_parser) {
            $this->_parser = new FTemplate_Parser();
        }

        return $this->_parser;
    }

    /**
     * Gets compiler
     * @return FTemplate_Compiler
     */
    public function getCompiler()
    {
        if (!$this->_compiler) {
            $this->_compiler = new FTemplate_Compiler();
        }

        return $this->_compiler;
    }

    protected function _compile($skel)
    {
        $this->getParser()->parse($skel);
        $this->getCompiler()->compile($skel);
    }
}