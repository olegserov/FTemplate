<?php
class FTemplate_Expression_OperatorsTest extends FTemplate_Test_Expression_BaseTest
{
    protected function _getObject()
    {
        return new FTemplate_Expression_Operators();
    }

    protected function _getPairs()
    {
        return array(
            'T_EXP || T_EXP' => 'T_EXP || T_EXP',
            'T_EXP * T_EXP' => 'T_EXP * T_EXP',
            'T_EXP * T_EXP / T_EXP' => 'T_EXP * T_EXP / T_EXP',
        );
    }
}