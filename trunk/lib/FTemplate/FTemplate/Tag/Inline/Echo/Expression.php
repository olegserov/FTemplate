<?php
class FTemplate_Tag_Inline_Echo_Expression implements FTemplate_Tag_Interface
{
    public function getTags()
    {
        return array(
            '' => 'echoExpression'
        );
    }

    public function echoExpression(FTemplate_Compiler_Context $context, $node)
    {

        $expressionCompiled = $context->getFactory()
            ->getExpression()
            ->parse($node->getBody());

        $node->setRaw(sprintf('<?=%s?>', $expressionCompiled));

        $context->appendNode($node);
    }
}