<?php
class FTemplate_Parser_Tags extends FTemplate_Parser_Base
{
    public function get(FTemplate_Template_Skel $skel)
    {
        foreach ($skel->tokens as $i => $token) {
            if ($token instanceof FTemplate_Token_Tag) {
                $skel->tokens[$i] = $this->_parseTag($token);
            } elseif ($token instanceof FTemplate_Token_Echo_Constant) {
                $skel->tokens[$i] = new FTemplate_Tag_Echo_Constant($token->getInput());
            } elseif ($token instanceof FTemplate_Token_Null) {
                $skel->tokens[$i] = new FTemplate_Tag_Null($token->getInput());
            } else {
                throw new FTemplate_Exception('Parser error: Undefined token: ' . print_r($token, 1));
            }
        }
    }

    protected function _parseTag(FTemplate_Token_Tag $token)
    {
        foreach ($this->_getTags() as $tag) {
            if (preg_match(
                '/^' . call_user_func(array($tag, 'getRegExp')) . '$/si',
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