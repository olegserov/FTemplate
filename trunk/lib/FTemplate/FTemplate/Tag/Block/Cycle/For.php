<?php
class FTemplate_Tag_Block_Logic_If extends FTemplate_Tag_Block_Base
{
    const TAG_FOR = 'tagFor';
    const TAG_ELSEFOR = 'tagElseFor';
    const TAG_ENDFOR = 'tagEndFor';

    public function getTags()
    {
        return array(
            'for' => self::TAG_FOR,

            'forelse' => self::TAG_FORELSE,

            'endfor' => self::TAG_ENDFOR,
            '/for' => self::TAG_ENDFOR,
        );
    }

    public function tagFor($context, $node)
    {
        $counter = null;
        $from = null;
        $to = null;

        // {for $i in 1..($i + 1)}
        $node->createStreamer()
            ->expectString('for')
            ->expectVar($counter)
            ->expectString('in')
            ->expectExperssion($from)
            ->expectString('..')
            ->expectExpression($to)
            ->expectdEnd();

        $node->setRaw(
            sprintf('$%s_from = %s; ', $counter, $from->compile())
            . sprintf('$%s_to = ; ', $counter, $to->compile())
            . sprintf('if ($%s_to <= $%s_from): ', $counter, $counter)
            . sprintf('for($%s = $%s_from; $%s <= $%s_to; $%s++): ', $counter, $counter, $counter, $counter, $counter)
        );

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