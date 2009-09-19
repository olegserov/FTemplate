<?php
class FTemplate_Expression_Var extends FTemplate_Expression
{
    const REGEX = '\$(\w+)((:?\.\w+|\[T_EXPRESSION\])+)';

    public function replace(array $matches)
    {
        // $xxx
        $return = '$this->_vars[\'' . $matches[1] . '\']';

        // a.b.c[T_EXPRESSION].e -> ['a']['b']['c'][T_EXPRESSION]['e']
        if (!empty($matches[2])) {
            $tmp = $matches[2];

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
