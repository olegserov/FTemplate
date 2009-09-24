<?php
class FTemplate_Expression_VarTest extends PHPUnit_Framework_TestCase
{
    public function testExpressions()
    {
        $replace = array(
            '$a' => '$this->_vars[\'a\']',
            '$a.b.c' => "\$this->_vars['a']['b']['c']",
            '$a.b[T_EXP]' => "\$this->_vars['a']['b'][T_EXP]",
            '$a.b[T_EXP][T_EXP]' => "\$this->_vars['a']['b'][T_EXP][T_EXP]",
            '$[T_EXP].b[T_EXP][T_EXP]' => "\$this->_vars[T_EXP]['b'][T_EXP][T_EXP]",
            '${T_EXP}' => "\$this->_vars[T_EXP]",
            '$b.c.x[T_EXP]' => "\$this->_vars['b']['c']['x'][T_EXP]",
            '$b[T_EXP]->sss' => "\$this->_vars['b'][T_EXP]->sss",
            '  $b.c.x[T_EXP] ' => "\$this->_vars['b']['c']['x'][T_EXP]",

            '$b.c.x[' => false,
            '$9b.c.x' => false,
            '$b.9c.x' => false,
            '$b->9c.x' => false,
            '$b.c.x{' => false,
            '$b.c.x->' => false,
            '$b.c.x.' => false,
        );

        $matches = array();
        foreach ($replace as $from => $to) {
            $res = preg_match('/' . FTemplate_Expression_Var::getRegExp() . '/x', $from, $matches);

            if ($to === false) {
                $this->assertTrue($res == 0, $from);
            } else {
                $this->assertTrue($res == 1, $from);
                $exp = new FTemplate_Expression_Var();
                $this->assertEquals($to, $exp->parse($matches));
            }
        }
    }
}