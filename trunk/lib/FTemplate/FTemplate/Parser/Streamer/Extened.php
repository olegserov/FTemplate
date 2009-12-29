<?php
class FTemplate_Parser_Streamer_Extened extends FTemplate_Parser_Streamer
{

    /**
     * Context
     * @var FTemplate_Compiler_Context
     */
    protected $_context;

    public function setContext($context)
    {
        $this->_context = $context;
    }

    /**
     * Expect a fixed string
     * @param $string
     * @param $named
     * @return FTemplate_Parser_Streamer_Extened
     */
    public function expectString($string, $named = null)
    {
        if (is_array($string)) {
            return $this->expect(
                join('|', array_map('preg_quote', $string)),
                $named
            );
        } else {
            return $this->expect(preg_quote($string), $named);
        }
    }

    public function expectEnd()
    {
        return $this->expect('$');
    }

    public function testEnd()
    {
        return $this->test('$');
    }

    protected function _error($msg)
    {
        $this->_context->error($msg);
    }


    public function expectExpression(& $expressionCompiled)
    {
        $expression = $this->_context->getFactory()->getExpression();

        if ($this->test(preg_quote('('))) {
            $this->expect(
                $expression->compileGlobalRegExp()
            );
            $expressionCompiled = $expression->parse(
                $this->_context,
                current($this->getCurrent())
            );
        } else {

        }

        return $this;
    }

    public function expectExpressionTillEnd(& $expressionCompiled)
    {
        $expression = $this->_context->getFactory()->getExpression();

        $this->expect('(.*)$');

        $expressionCompiled = $expression->parse(
            $this->_context,
            next($this->getCurrent())
        );

        return $this;
    }
}