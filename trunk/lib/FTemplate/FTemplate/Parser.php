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
     * Parse template
     *
     * @param $content
     * @return array tree
     */
    public function parse($content)
    {
        return

        $this->_parserTree->get(
            $this->_parserTags->get(
                $this->_parserTokens->get(
                    $this->_parserChunks->get($content)
                )
            )
        );
    }
}