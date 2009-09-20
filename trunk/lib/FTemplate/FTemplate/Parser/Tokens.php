<?php
class FTemplate_Parser_Tokens extends FTemplate_Parser_Base
{
    public function get(array $chunks)
    {
        $tokens = array();

        $i = 0;

        $line = 1;

        foreach ($chunks as $chunk) {
            $i++;
            if ($chunk === '') continue;
            if ($i % 2 == 1) {
                $tokens[] = new FTemplate_Token_Echo_Constant($chunk, $line);
            } else {
                $tokens[] = $this->_getToken($chunk, $line);
            }

            $line += substr_count("\n", $chunk);
        }

        return $tokens;
    }

    protected function _getToken($chunk, $line)
    {
        $matches = array();
        foreach($this->_tokenList() as $reg => $class) {
            if (preg_match($this->_makeRegex($reg), $chunk, $matches)) {
                return new $class($matches[1], $line);
            }
        }

        throw new Exception('Undefined chunk: ' . $chunk);
    }


    protected function _tokenList()
    {
        return array(
            '/^ TAG_COMMENT_OPEN ( SOMETHING ) TAG_COMMENT_CLOSE $/sx' => 'FTemplate_Token_Null',
            '/^ TAG_LITERAL_OPEN ( SOMETHING ) TAG_LITERAL_CLOSE $/sx' => 'FTemplate_Token_Echo_Constant',
            '/^ TAG_OPEN ( SOMETHING ) TAG_CLOSE $/sx' => 'FTemplate_Token_Tag',
        );
    }

}