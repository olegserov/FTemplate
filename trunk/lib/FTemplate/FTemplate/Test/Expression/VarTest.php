<?php
class FTemplate_Expression_VarTest extends FTemplate_Test_Expression_BaseTest
{
    protected function _getObject()
    {
        return new FTemplate_Expression_Argument_Var();
    }

    protected function _getPairs()
    {
        return array(
            '$a' => '$this->_env->vars[\'a\']',
            '$a.b.c' => "\$this->_env->vars['a']['b']['c']",
            '$a.b' => "\$this->_env->vars['a']['b']",
            '$a.b[T_EXP]' => "\$this->_env->vars['a']['b'][T_EXP]",
            '$a.b[T_EXP][T_EXP]' => "\$this->_env->vars['a']['b'][T_EXP][T_EXP]",
            '$[T_EXP].b[T_EXP][T_EXP]' => "\$this->_env->vars[T_EXP]['b'][T_EXP][T_EXP]",
            '${T_EXP}' => "\$this->_env->vars[T_EXP]",
            '$b.c.x[T_EXP]' => "\$this->_env->vars['b']['c']['x'][T_EXP]",
            '$b[T_EXP]->sss' => "\$this->_env->vars['b'][T_EXP]->sss",
            '  $b.c.x[T_EXP] ' => "\$this->_env->vars['b']['c']['x'][T_EXP]",
            '  $b->{T_EXP} ' => "\$this->_env->vars['b']->{T_EXP}",


            '$from..' => false,
            '$$from' => false,
            '$b.c.x[' => false,
            '$9b.c.x' => false,
            '$b.9c.x' => false,
            '$b->9c.x' => false,
            '$b.c.x{' => false,
            '$b.c.x->' => false,
            '$b.c.x.' => false,
        );
    }
}