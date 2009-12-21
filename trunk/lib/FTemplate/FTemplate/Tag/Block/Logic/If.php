<?php
class FTemplate_Tag_Block_Logic_If extends FTemplate_Tag_Block_Base
{
    public function getTags()
    {
        return array(
            'if' => 'parseIf',
            'elseif' => 'parseElseIf',
            'else' => 'parseElse',
            'endif' => 'parseEndIf',
            '/if' => 'paseEndIf'
        );
    }

    public function parseIf(FTemplate_Compiler_Parser_Streamer_Extened $streamer)
    {
        $streamer->expectString('if');
        $expression = $streamer->expectExpression();
        $streamer->expectEnd();

        return sprintf('<?if (%s): ?>', $expression->compile());
    }

    public function parseEndIf(FTemplate_Compiler_Parser_Streamer_Extened $streamer)
    {
        $streamer->expect('endif|\/if');

        return '<?endif;?>';
    }
}