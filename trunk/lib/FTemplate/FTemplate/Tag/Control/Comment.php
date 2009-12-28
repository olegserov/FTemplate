<?php
class FTemplate_Tag_Control_Comment implements FTemplate_Tag_Interface
{
    public function getTags()
    {
        return array(
            '\*' => 'tagComment',
        );
    }

    public function tagComment($context, $node)
    {
        $context->appendNode($node);
    }
}