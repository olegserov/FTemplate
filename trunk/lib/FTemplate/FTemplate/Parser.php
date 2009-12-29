<?php
class FTemplate_Parser extends FTemplate_Base
{
    /**
     * Tags
     * @var FTemplate_Tag_Interface[][]
     */
    protected $_tags;

    protected $_rawRegExp;

    protected $_parseChunkRegExp;

    protected $_tagEchoConstant;

    /**
     * Inits the world!
     * @return void
     */
    protected function _init()
    {
        $this->_rawRegExp = $this->_makeRegex(
        "{
        (
            (
                TAG_OPEN
                (
                    (?>[^{}'\"]*)             # All, except: \", ', TAG_OPEN, TAG_CLOSE
                    | \"([^\"\\\\]*|\\\\.)*\" # Double quoted string
                    | '([^'\\\\]*|\\\\.)*'    # Single quoted string
                    | (?2)*                   # Recursive
                )*
                TAG_CLOSE
            )
            |  TAG_COMMENT_OPEN SOMETHING TAG_COMMENT_CLOSE
            |  TAG_LITERAL_OPEN SOMETHING TAG_LITERAL_CLOSE
        )
        }six"
        );

        $this->_tagEchoConstant = new FTemplate_Tag_Inline_Echo_Constant();

        $this->_parseChunkRegExp = $this->_makeRegex(
            '{^ TAG_OPEN (%s SOMETHING) TAG_CLOSE $}six'
        );
    }

    /**
     * Adds tag to parser
     * @param $tag
     * @param $order
     * @return void
     */
    public function addTag($tag, $order = 10)
    {
        if (!($tag instanceof FTemplate_Tag_Interface)) {
            throw new Exception(get_class($tag) . ' is not instance of FTemplate_Tag_Interface');
        }

        if (is_object($tag)) {
            ; // none
        } elseif (!class_exists($tag)) {
            throw new Exception($tag . ' not found');
        } else {
            $tag = new $tag;
        }

        foreach ($tag->getTags() as $regex => $callback) {
            $this->_tags[$order][$regex] = array($tag, $callback);
        }

        ksort($this->_tags);
    }

    /**
     * Main parse algoritm
     * @param $skel
     * @return void
     */
    public function parse(FTemplate_Template_Skel $skel)
    {
        $this->_parseRaw($skel);
        $this->_parseChunks($skel);
    }

    /**
     * Parse raw data into chunks
     * @param FTemplate_Template_Skel $skel
     * @return void
     */
    protected function _parseRaw(FTemplate_Template_Skel $skel)
    {
        $skel->chunks = preg_split(
            $this->_rawRegExp,
            $skel->getFileContent(),
            0,
            PREG_SPLIT_DELIM_CAPTURE
                | PREG_SPLIT_OFFSET_CAPTURE

        );
    }

    /**
     * Parse chunks into tags and sets context.
     * @param FTemplate_Template_Skel $skel
     * @return void
     */
    protected function _parseChunks(FTemplate_Template_Skel $skel)
    {
        // @todo remove it!
        $skel->context = $this->_factory->createContext();
        $skel->context->setSkel($skel);

        $i = 0;

        $line = 1;

        $current = 0;

        foreach ($skel->chunks as $chunk) {
            /**
             * See http://bugs.php.net/bug.php?id=50605
             */
            if ($chunk[1] != $current) {
                continue;
            }

            $current = $chunk[1] + strlen($chunk[0]);

            $i++;

            if ($chunk[0] === '') continue;

            if ($i % 2 == 1) {
                $this->_createTag(
                    $this->_tagEchoConstant,
                    'echoRaw',
                    $skel->context,
                    $skel->context->createNode($chunk[0], $line)
                );
            } else {
                $this->_parseChunk($chunk[0], $line, $skel->context);
            }

            $line += substr_count($chunk[0], "\n");
        }

        $skel->chunks = null;
    }

    protected function _parseChunk($chunk, $line, $context)
    {
        $node = $context->createNode($chunk, $line);

        $matches = array();

        foreach ($this->_tags as $group) {
            foreach ($group as $regex => $ob) {
                if (preg_match(
                    sprintf($this->_parseChunkRegExp, $regex),
                    $chunk,
                    $matches
                )) {
                    $node->setBody($matches[1]);
                    $this->_createTag($ob[0], $ob[1], $context, $node);
                    return;
                }
            }
        }

        $context->error('Tag could not to be parsed');
    }

    /**
     * Create tag
     * @param $tagClass
     * @param $tagMethod
     * @param $context
     * @param $node
     * @return void
     */
    protected function _createTag($tagClass, $tagMethod, $context, $node)
    {
        $node->setClass($tagClass);
        $node->setType($tagMethod);

        $tagClass->$tagMethod($context, $node);
    }

    protected function _makeRegex($str)
    {
        $dic = array(
            'TAG_OPEN' => '\\{',
            'TAG_CLOSE' => '\\}',
            'TAG_COMMENT_OPEN' => '\\{\\*',
            'TAG_COMMENT_CLOSE' => '\\*\\}',
            'TAG_LITERAL_OPEN' => '\\{%',
            'TAG_LITERAL_CLOSE' => '%\\}',
            'SOMETHING' => '.*?'
        );

        $str = strtr($str, $dic);
        return $str;
    }
}