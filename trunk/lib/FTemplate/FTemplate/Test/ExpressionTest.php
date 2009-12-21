<?php
class FTemplate_Test_ExpressionTest extends PHPUnit_Framework_TestCase
{
    public function testGeneral()
    {
        $exp = new FTemplate_Expression();
        $pairs = array(
            '4' => '4',
            '$a.b' => '$this->_vars[\'a\'][\'b\']',
            '1 + 3 * 4' => '(1 + (3 * 4))',
            '1 .. 3 * 4 % 1' => '(1 . (3 * 4 % 1))',
            '(1 .. 3) * 4 % 1' => '((((1 . 3))) * (4 % 1))',

        );

        foreach ($pairs as $from => $to) {
            $this->assertEquals(
                $to,
                $exp->parse($from, null),
                $from
            );
        }
    }
}