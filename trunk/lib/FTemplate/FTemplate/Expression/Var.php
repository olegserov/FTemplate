<?php
class FTemplate_Expression_Var extends FTemplate_Expression_Base
{

    public static function getRegExp()
    {
        return '\$(\w+)((:?\.\w+|\[T_EXPRESSION\])*)(?=[^\.\[])';
    }

    public function parse(array $matches)
    {
        // $xxx
        $return = '$this->_vars[\'' . $matches[1] . '\']';

        // a.b.c[T_EXPRESSION].e -> ['a']['b']['c'][T_EXPRESSION]['e']
        //@todo add support of: $a.b.c->e.g[T_EXPRESSION]->gg;
        if (!empty($matches[2])) {
            $tmp = ltrim($matches[2], '.');

            $tmp = strtr($tmp, array(
                '[' => "'][",
                '].' => "]['",
                '.' => "']['"
            ));

            $return .= "['$tmp']";
        }

        return $return;
    }

}
