<?php
class FTemplate_Tag_Block_Cycle_ForEach implements FTemplate_Tag_Interface
{
    const TAG_FOREACH = 'tagForEach';
    const TAG_FOREACHELSE = 'tagForEachElse';
    const TAG_ENDFOREACH = 'tagEndForEach';

    public function getTags()
    {
        return array(
            'for-?each' => self::TAG_FOREACH,
            'foreachelse' => self::TAG_FOREACHELSE,
            '(/|end)foreach' => self::TAG_ENDFOREACH,
        );
    }

    public function tagForEach($context, $node)
    {
        $from = $key = $value = null;

        $streamer = $node->createStreamer();

        // {foreach $val as $key => $value}
        $streamer->expect('for-?each')
            ->expectExpression($from)
            ->expectString('as')
            ->expectVar($key);


        if ($streamer->testString('=>')) {
            $streamer->movePointer();
            $streamer->expectVar($value);
        } else {
            $value = $key;
            $key = null;
        }

        $streamer->expectEnd();

        $code = sprintf(
            '<?$_tmp = %s; if ((is_array($_tmp) || is_object($_tmp)) && count($_tmp) > 0):',
            $from
        );

        if ($key == null) {
            $code .= sprintf(
                'foreach ($_tmp as %s): ',
                $value
            );
        } else {
            $code .= sprintf(
                'foreach ($_tmp as %s => %s): ',
                $key,
                $value
            );
        }

        $context->appendNode($node);
        $context->levelDown();
    }

    public function tagForEachElse($context, $node)
    {
        $node->createStreamer()
            ->expectString('foreachelse')
            ->expectEnd();

        $node->setRaw('<?endforeach; else: ?>');

        $context->levelUp(
            $node,
            array(
                self::TAG_FOREACH
            )
        );

        $context->appendNode($node);
        $context->levelDown();
    }

    public function tagEndForEach($context, $node)
    {
        $node->createStreamer()
            ->expectString(array('endforeach', '/foreach'))
            ->expectEnd();

        $openTag = $context->levelUp(
            $node,
            array(
                self::TAG_FOREACH,
                self::TAG_FOREACHELSE
            )
        );

        if ($openTag->getType() == self::TAG_FOREACH) {
            $node->setRaw('<?endforeach; endif;');
        } else {
            $node->setRaw('<?endforeach;?>');
        }

        $context->appendNode($node);

    }

}