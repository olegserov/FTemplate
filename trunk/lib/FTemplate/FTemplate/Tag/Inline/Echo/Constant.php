<?php
class FTemplate_Tag_Inline_Echo_Constant
{
    public function getTags()
    {
        return array();
    }

    public function echoRaw($context, $node)
    {
        $node->setRaw(
            str_replace('<?', '<<??>?', $node->getChunk())
        );

        $context->appendNode($node);
    }
}