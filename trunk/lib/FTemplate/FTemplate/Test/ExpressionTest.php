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

    public function testGlobalRegExp()
    {
        $assertsTrue = array(
            '(a + b) + d' => '(a + b)',
            '(a + (b + c)) * gg' => '(a + (b + c))',
            '(a + (b + c) + "gg") * gg' => '(a + (b + c) + "gg")',
            '(a + (b + c) + \'gg\') * gg' => '(a + (b + c) + \'gg\')',
            '(a + ")") * gg' => '(a + ")")',
            '("ggg") + "ff" )' => '("ggg")',
            '("g\"g\g") + "ff" )' => '("g\"g\\g")',
            '(a + "(") + ")" * gg' => '(a + "(")',
            '(a + "(" + "("  + ) + ")" * gg' => '(a + "(" + "("  + )',
            '($i ^ (1 + $i)) .. ($i)' => '($i ^ (1 + $i))'
        );

        $exp = new FTemplate_Expression();
        $regex = $exp->compileGlobalRegExp();

        $matches = array();

        foreach ($assertsTrue as $assert => $expect) {
            $res = preg_match('{^(?:' . $regex . ')}six', $assert, $matches);
            $this->assertTrue($res === 1, $assert);
            $this->assertEquals($expect, $matches[0], var_Export(array($assert, $matches), 1));
        }
    }
}