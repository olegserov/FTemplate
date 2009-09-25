<?php
class FTemplate_Expression_ConsantTest extends FTemplate_Test_Expression_BaseTest
{
    protected function _getObject()
    {
        return new FTemplate_Expression_Constant();
    }

    protected function _getPairs()
    {
        return array(
            'T_EXP' => 'T_EXP',
            '_EXP' => '_EXP',
            '_EXPsss' => '_EXPsss',
        );
    }
}