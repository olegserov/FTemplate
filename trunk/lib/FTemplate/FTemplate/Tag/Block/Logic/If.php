<?php
class FTemplate_Tag_Block_Logic_If extends FTemplate_Tag_Block_Base
{
    const TAG_IF = 'tagIf';
    const TAG_ELSEIF = 'tagElseIf';
    const TAG_ELSE = 'tagElse';
    const TAG_ENDIF = 'tagEndIf';

    public function getTags()
    {
        return array(
            'if' => self::TAG_IF,

            'elseif' => self::TAG_ELSEIF,

            'else' => self::TAG_ELSE,

            'endif' => self::TAG_ENDIF,
            '/if' => self::TAG_ENDIF
        );
    }

    public function tagIf($context, $node)
    {
        $expression = null;

        $node->createStreamer()
            ->expectString('if')
            ->expectExpression($expression)
            ->expectEnd();

        $node->setRaw(sprintf('<?if (%s):?>', $expression->compile()));

        $context->appendNode($node);
        $context->levelDown();
    }

    public function tagElseIf($context, $node)
    {
        $expression = null;

        $node->createStreamer()
            ->expectString('elseif')
            ->expectExpression($expression)
            ->expectEnd();

        $node->setRaw(sprintf('<?elseif (%s):?>', $expression->compile()));

        $context->levelUp(
            $node,
            array(
                self::TAG_IF,
                self::TAG_ELSEIF
            )
        );
        $context->levelDown();
    }

    public function tagElse($context, $node)
    {
        $node->createStreamer()
            ->expectString('else')
            ->expectEnd();

        $node->setRaw('<?else:?>');

        $context->levelUp(
            $node,
            array(
                self::TAG_IF,
                self::TAG_ELSEIF
            )
        );
        $context->appendNode($node);
        $context->levelDown();
    }

    public function tagEndIf($context, $node)
    {
        $node->createStreamer()
            ->expectString(array('/if', 'endif'))
            ->expectEnd();

        $node->setRaw('<?endif;?>');

        $context->levelUp(
            $node,
            array(
                self::TAG_IF,
                self::TAG_ELSEIF,
                self::TAG_ELSE
            )
        );
    }
}