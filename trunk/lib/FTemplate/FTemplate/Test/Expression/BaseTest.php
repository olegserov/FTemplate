<?php
abstract class FTemplate_Test_Expression_BaseTest extends PHPUnit_Framework_TestCase
{
    abstract protected function _getObject();

    abstract protected function _getPairs();

    public function testExpressions()
    {
        $expression = $this->_getObject();

        $reg_exps = array();
        foreach((array) $expression->getRegExp() as $exp) {
            $reg_exps[] = '{' . $exp . '}x';
        }


        $this->assertTrue(count($this->_getPairs()) > 0);

        $matches = array();
        foreach ($this->_getPairs() as $from => $to) {
            foreach ($reg_exps as $exps) {
                $res = preg_match($exps, $from, $matches);

                if ($res) {
                    break;
                }
            }

            if ($to === false) {
                $this->assertEquals(0, $res, $from);
            } else {
                $this->assertEquals(1, $res, var_export(array($from, $reg_exps), 1));
                $this->assertEquals($to, $expression->compile($matches));
            }
        }

    }
}