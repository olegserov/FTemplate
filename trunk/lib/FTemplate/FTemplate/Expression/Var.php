<?php
class FTemplate_Expression_Var extends FTemplate_Expression_Base
{

    public static function getRegExp()
    {
        /**
         * Allow:
         * $varname
         * $array.key
         * $array.key.subkey
         * $array[$key].subkey
         * $[$array];
         * $class->param
         * $class->{$ob}
         *
         */
        return '
            \$(
                (?:
                    \[T_EXPRESSION\]
                    | \{T_EXPRESSION\}
                    | [a-zA-Z]\w*
                )
                (?:
                    \[T_EXPRESSION\]
                    | \.[a-zA-Z]\w*
                    | ->[a-zA-Z]\w*
                    | ->\{T_EXPRESSION\}
                )*
            )

            (?!(\[|\{|\.|->))
        ';
    }

    public function parse(array $matches)
    {
        // $xxx
        $return = '$this->_vars';

        $matches = preg_split(
            '/
            (
                \]
                | \[
                | \{
                | \}
                | ->
                | \{
                | \.
            )
            /x',
            $matches[1],
            null,
            PREG_SPLIT_DELIM_CAPTURE
                | PREG_SPLIT_NO_EMPTY
        );

        $exp = false;
        $object = false;

        foreach ($matches as $item) {
            switch ($item) {
                case ']': case '}': continue;

                case '[':
                    $exp = true;
                    $object = false;
                    break;

                case '{':
                    $exp = true;
                    break;

                case '.':
                    $exp = false;
                    $object = false;
                    break;

                case '->':
                    $exp = false;
                    $object = true;
                    break;

                default:
                    if ($object && $exp) {
                        $return .= '->{' . $item . '}';
                    } elseif ($object && !$exp) {
                        $return .= '->' . $item;
                    } elseif (!$object && $exp) {
                        $return .= '[' . $item . ']';
                    } elseif (!$object && !$exp) {
                        $return .= '[\'' . $item . '\']';
                    }
            }

            //echo "$return\n";
        }


        return $return;
    }

}
