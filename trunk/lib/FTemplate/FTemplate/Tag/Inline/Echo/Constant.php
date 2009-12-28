<?php
class FTemplate_Tag_Inline_Echo_Constant implements FTemplate_Tag_Interface
{
    public function getTags()
    {
        return array();
    }

    public function echoRaw($context, $node)
    {
        $node->setRaw(
            str_replace('<?', '<<??>?', $node->getBody())
        );

        $context->appendNode($node);
    }
}