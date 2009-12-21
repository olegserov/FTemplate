<?php
class FTemplate_Compiler_Parser_Streamer_Extened
{
    /**
     * Expect a fixed string
     * @param $string
     * @param $named
     * @return FTemplate_Compiler_Parser_Streamer_Extened
     */
    public function expectString($string, $named = null)
    {
        return $this->expect(preg_quote($string), $named);
    }
}