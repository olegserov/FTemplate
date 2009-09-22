<?php
class FTemplate_Expression_VarTest extends PHPUnit_Framework_TestCase
{
    public function testExpressions()
    {
        $replace = array(
            '$a' => '$this->_vars[\'a\']',
            '$a.b.c' => "\$this->_vars['a']['b']['c']",
            '$a.b[T_EXPRESSION]' => "\$this->_vars['a']['b'][T_EXPRESSION]",
            '$a.b[T_EXPRESSION][T_EXPRESSION]' => "\$this->_vars['a']['b'][T_EXPRESSION][T_EXPRESSION]",
            '$[T_EXPRESSION].b[T_EXPRESSION][T_EXPRESSION]' => "\$this->_vars[T_EXPRESSION]['b'][T_EXPRESSION][T_EXPRESSION]",
            '${T_EXPRESSION}' => "\$this->_vars[T_EXPRESSION]",
            '$b.c.x[T_EXPRESSION]' => "\$this->_vars['b']['c']['x'][T_EXPRESSION]",
            '$b[T_EXPRESSION]->sss' => "\$this->_vars['b'][T_EXPRESSION]->sss",
            '  $b.c.x[T_EXPRESSION] ' => "\$this->_vars['b']['c']['x'][T_EXPRESSION]",

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