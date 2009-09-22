<?php
/**
 * right    !    logical
left    * / %   arithmetic
left    + - .   arithmetic and string
left    << >>   bitwise
non-associative     < <= > >= <>    comparison
non-associative     == != === !==   comparison
left    &   bitwise and references
left    ^   bitwise
left    |   bitwise
left    &&  logical
left    ||  logical
left    ? :     ternary
right   = += -= *= /= .= %= &= |= ^= <<= >>=    assignment
left    and     logical
left    xor     logical
left    or  logical
left    ,   many uses
 * @author Ose
 *
 */
class FTemplate_Expression_Operators extends FTemplate_Expression_Base
{
    // note that '..' is string concat.
    protected static $_operators = array(
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

    public static function getRegExp()
    {
        $return = array();
        foreach (self::$_operators as $op) {
            $op = explode(' ', preg_quote($op, '/'));
            $op = '(' . join('|', $op) . ')';
            $return = "
                (?!$op)
                (
                    T_EXPRESSION
                    ($op T_EXPRESSION)+
                )
                (?!$op)
            ";
        }
        return $return;
    }

    public function parse(array $matches)
    {
        return $matches[0];
    }

}