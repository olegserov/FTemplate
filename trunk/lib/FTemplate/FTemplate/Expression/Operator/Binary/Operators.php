<?php
class FTemplate_Expression_Operator_Binary_Operators implements FTemplate_Expression_Interface
{
    // note that '..' is string concat.
    protected $_operators = array(
        '* / %',
        '+ - ..',
        '<< >>',
        '< <= > >= <>',
        '== != === !==',
        '&',
        '^',
        '|',
        '&&',
        '||'
    );

    public function getRegExp()
    {
        $return = array();
        foreach ($this->_operators as $op) {
            $op = explode(' ', preg_quote($op));
            $op = '(' . join('|', $op) . ')';
            $return[] = "

                (
                    T_EXP
                    (\\s* $op \\s* T_EXP)+
                )

            ";
        }

        return $return;
    }

    public function compile(array $matches)
    {
        return '(' . str_replace('..', '.', $matches[0]) . ')';
    }

}