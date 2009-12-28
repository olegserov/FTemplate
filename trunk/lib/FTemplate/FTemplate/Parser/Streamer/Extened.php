<?php
class FTemplate_Parser_Streamer_Extened
{
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
                join('|', array_map($string, 'preg_qutoe')),
                $named
            );
        } else {
            return $this->expect(preg_quote($string), $named);
        }
    }

    public function expectExpression(& $expression)
    {
        if ($this->test(preg_quote('('))) {
            $this->expect()
        } else {

        }
    }
}