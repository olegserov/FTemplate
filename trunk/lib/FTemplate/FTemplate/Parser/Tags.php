<?php
class FTemplate_Parser_Tags extends FTemplate_Parser_Base
{
    public function get(array $tokens)
    {
        foreach ($tokens as $i => $token) {
            if ($token instanceof FTemplate_Token_Tag) {
                $tokens[$i] = $this->_parseTag($token);
            } elseif ($token instanceof FTemplate_Token_Echo_Constant) {
                $tokens[$i] = new FTemplate_Tag_Echo_Constant($token->getInput());
            } elseif ($token instanceof FTemplate_Token_Null) {
                $tokens[$i] = new FTemplate_Tag_Null($token->getInput());
            } else {
                throw new Exception('wtf?');
            }
        }

        return $tokens;
    }

    protected function _parseTag(FTemplate_Token_Tag $token)
    {
        foreach ($this->_getTags() as $tag) {
            if (preg_match(
                '/^' . call_user_func(array($tag, 'getRegEx')) . '$/si',
                $token->getInput()
            )) {
                return new $tag($token->getInput());
            }
        }

        throw new Exception('Wtf?');
    }

    protected function _getTags()
    {
        return array(
            'FTemplate_Tag_Echo_Expression'
        );
    }
}