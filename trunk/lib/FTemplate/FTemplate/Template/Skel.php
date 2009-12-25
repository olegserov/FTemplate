<?php
class FTemplate_Template_Skel
{
    protected $_file;

    protected $_fileStamp;

    protected $_class;

    protected $_compiledCode;

    public function __construct($origFile)
    {
        $file = realpath($origFile);

        if (!$file) {
            throw new FTemplate_Exception('File: ' . $origFile . ' Not found');
        }

        $this->_file = $file;
        $this->_fileStamp = filemtime($file);
        $this->_class = 'FTemplate_Compiled_' . preg_replace('/\W/', '_', $file);
    }

    public function getClassName()
    {
        return $this->_class;
    }

    public function getFile()
    {
        return $this->_file;
    }

    public function getFileStamp()
    {
        return $this->_fileStamp;
    }

    public function getFileContent()
    {
        return file_get_contents($this->_file);
    }

    public function setCode($code)
    {
        $this->_compiledCode = $code;
    }

    public function getCode()
    {
        return $this->_compiledCode;
    }

    public function createObject($args)
    {
        return new $this->_class($args);
    }

    public function evalCode()
    {
        eval('?>' . $this->getCode());
    }
}