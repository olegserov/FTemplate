<?php
class FTemplate_Parser extends FTemplate_Manager
{
    /**
     * Tags
     * @var FTemplate_Tag_Interface[][]
     */
    protected $_tags;

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

    public function parse(FTemplate_Template_Skel $skel)
    {
        $this->_parseRaw($skel);
        $this->_parseChunks($skel);
    }

    protected function _parseRaw(FTemplate_Template_Skel $skel)
    {
        $skel->chunks = preg_split(
            $this->_makeRegex('/(
                  TAG_OPEN SOMETHING TAG_CLOSE
               |  TAG_COMMENT_OPEN SOMETHING TAG_COMMENT_CLOSE
               |  TAG_LITERAL_OPEN SOMETHING TAG_LITERAL_CLOSE
            )/sx'),
            $skel->getFileContent(),
            0,
            PREG_SPLIT_DELIM_CAPTURE
        );
    }

    protected function _parseChunks(FTemplate_Template_Skel $skel)
    {
        $skel->context = new FTemplate_Compiler_Context($skel);

        $i = 0;

        $line = 1;

        foreach ($skel->chunks as $chunk) {
            $i++;

            if ($chunk === '') continue;

            if ($i % 2 == 1) {
                $this->_createTag(
                    '',
                    'echoRaw',
                    $skel->context,
                    $skel->context->createNode($chunk, $line)
                );
            } else {
                $this->_parseChunk($chunk, $line, $skel->context);
            }

            $line += substr_count("\n", $chunk);
        }

        $skel->chunks = null;
    }

    protected function _parseChunk($chunk, $line, $context)
    {
        $node = $context->createNode($chunk, $line);

        foreach ($this->_tags as $group) {
            foreach ($group as $regex => $ob) {
                if (preg_match($regex, $chunk)) {
                    $this->_createTag($ob[0], $ob[1], $context, $node);
                    return;
                }
            }
        }

        $context->error('Error! unknown tag!');
    }

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
            'TAG_CLOSE' => '\\}\n?',
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