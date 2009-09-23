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

    protected function _loadFile($origFile)
    {
        $file = realpath($origFile);

        if (!$file) {
            throw new Exception('File: ' . $origFile . ' Not found');
        }

        $file_m_time = filemtime($file);
        $class_name = $this->_fileToClass($file);

        // Loaded in php
        if (class_exists($class_name, false)) {
            return $class_name;
        }

        // Cache driver is not inited
        if ($this->_cacheDriver === null) {
            $this->_cacheDriver = new FTemplate_Cache_FS();
        }



        // Caching is allowed
        if ($this->_cacheDriver) {
            if ($this->_cacheDriver->load($class_name, $file_m_time)) {
                return $class_name;
            }
        }

        $code = $this->_compile(file_get_contents($file), $class_name);

        //Caching is allowed
        if ($this->_cacheDriver) {
            $this->_cacheDriver->save($class_name, $file_m_time, $code);
        }

        //echo $code;

        eval($code);



        return $class_name;
    }

    public function display($origFile)
    {
        $class_name = $this->_loadFile($origFile);
        $this->_call($class_name);
    }

    protected function _call($className, $method = 'main')
    {
        $ob = new $className($this->_vars);
        $ob->$method();
    }

    protected function _fileToClass($file)
    {
        return 'FTemplate_Compiled_' . preg_replace('/\W/', '_', $file);
    }

    protected function _compile($text, $className)
    {
        $parser = new FTemplate_Parser();
        $tree = $parser->parse($text);

        $compiler = new FTemplate_Compiler();
        $code = $compiler->compile($tree, $className);

        return $code;
    }
}