<?php
class FTemplate_Parser
{

    /**
     * @var FTemplate_Parser_Chunks
     */
    protected $_parserChunks;

    /**
     * @var FTemplate_Parser_Tags
     */
    protected $_parserTags;

    /**
     * @var FTemplate_Parser_Tokens
     */
    protected $_parserTokens;

    /**
     * @var FTemplate_Parser_Tree
     */
    protected $_parserTree;


    /**
     * Expression parser
     * @var FTemplate_Expression
     */
    protected $_parserExpression;

    /**
     * Construct
     *
     */
    public function __construct()
    {
        $this->_parserChunks = new FTemplate_Parser_Chunks($this);
        $this->_parserTokens = new FTemplate_Parser_Tokens($this);
        $this->_parserTags = new FTemplate_Parser_Tags($this);
        $this->_parserTree = new FTemplate_Parser_Tree($this);
    }

    /**
     * Gets expression parser
     * @return FTemplate_Expression
     */
    public function getExpressionParser()
    {
        if (!$this->_parserExpression) {
            $this->_parserExpression = new FTemplate_Expression($this);
        }

        return $this->_parserExpression;
    }

    public function parse(FTemplate_Template_Skel $skel)
    {
        $this->_parserChunks->get($skel);
        $this->_parserTokens->get($skel);
        $this->_parserTags->get($skel);
        $this->_parserTree->get($skel);
    }
}