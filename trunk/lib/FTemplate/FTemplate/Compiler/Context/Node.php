<?php
class FTemplate_Compiler_Context_Node
{
    protected $_context;

    protected $_parent;

    protected $_class;

    protected $_type;

    protected $_rawCode;

    protected $_chunk;

    protected $_line;

    protected $_body;

    protected $_index;


    public function __toString()
    {
        return sprintf(
            'Node: %s::%s; Line: %d; Chunk: %s',
            get_class($this->_class),
            $this->_type,
            $this->_line,
            $this->_chunk
        );
    }

    public function __construct($context, $chunk, $line)
    {
        $this->_chunk = $chunk;
        $this->_line = $line;
        $this->_context = $context;
    }

    public function setParent(FTemplate_Compiler_Context_NodeList $parent, $index)
    {
        $this->_parent = $parent;
        $this->_index = $index;
    }


    public function getIndex()
    {
        return $this->_index;
    }

    public function getParent()
    {
        return $this->_parent;
    }

    public function getContext()
    {
        return $this->_context;
    }

    public function setChunk($chunk, $line)
    {
        $this->_chunk = $chunk;
        $this->_line = $line;
    }

    public function setClass($class)
    {
        $this->_class = $class;
    }

    public function setType($type)
    {
        $this->_type = $type;
    }

    public function setBody($body)
    {
        $this->_body = $body;
    }

    public function getBody()
    {
        return $this->_body;
    }

    public function getChunk()
    {
        return $this->_chunk;
    }

    public function getLine()
    {
        return $this->_line;
    }

    public function setRaw($raw)
    {
        $this->_rawCode = $raw;
    }

    public function echoRaw()
    {
        echo $this->_rawCode;
    }

    public function getClass()
    {
        return $this->_class;
    }

    public function getType()
    {
        return $this->_type;
    }
    /**
     * Creates streamer with current body
     * @return FTemplate_Parser_Streamer_Extened
     */
    public function createStreamer()
    {
        $streamer = new FTemplate_Parser_Streamer_Extened($this->_body);
        $streamer->setContext($this->_context);
        return $streamer;
    }
}