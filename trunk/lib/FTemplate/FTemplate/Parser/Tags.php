<?php
class FTemplate_Parser_Tags extends FTemplate_Parser_Base
{
    public function get(FTemplate_Template_Skel $skel)
    {
        foreach ($skel->tokens as $i => $token) {
            if ($token instanceof FTemplate_Token_Tag) {
                $skel->tokens[$i] = $this->_parseTag($token);
            } elseif ($token instanceof FTemplate_Token_Echo_Constant) {
                $skel->tokens[$i] = new FTemplate_Tag_Echo_Constant($token);
            } elseif ($token instanceof FTemplate_Token_Null) {
                $skel->tokens[$i] = new FTemplate_Tag_Null($token);
            } else {
                throw new FTemplate_Exception('Parser error: Undefined token: ' . print_r($token, 1));
            }
        }
    }

    protected function _parseTag(FTemplate_Token_Tag $token)
    {
        foreach ($this->_getTags() as $tag) {
            $reg_exps = (array) call_user_func(array($tag, 'getRegExp'));
            foreach ($reg_exps as $key => $reg_exp) {
                if (preg_match(
                    $reg_exp,
                    $token->getInput()
                )) {
                    return new $tag($token, $key);
                }
            }

        }

        throw new Exception('Wtf?');
    }

    protected function _getTags()
    {
        return array(
            'FTemplate_Tag_Block_Cycle_For',
            'FTemplate_Tag_Echo_Expression',
        );
    }
}