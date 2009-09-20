<?php
class FTemplate_Expression
{
    protected $_map;

    protected $_lastName;

    protected $_expression;

    protected function _parseToken(array $matches)
    {
        $this->_lastName = '~' . count($this->_map) . '~';
        $this->_map[$this->_lastName] = $this->_expression->parse($matches);
        return $this->_lastName;
    }

    public function parse($input, $context)
    {
        if (empty($input)) {
            throw new Exception('Empty input');
        }

        $count = 0;

        do {
            foreach ($this->_getExpressions() as $exp) {
                $this->_expression = $exp;
                $reg_exp = $exp->getRegExp();
                $reg_exp = str_replace('T_EXPRESSION', "~[0-9]+~", $reg_exp);

                $input = preg_replace_callback(
                    '/\s*' . $reg_exp . '\s*/six',
                    array($this, '_parseToken'),
                    ' ' . $input . ' ',
                    -1,
                    $count
                );

                if ($count) {
                    continue;
                }
            }
        } while ($count);

        if (trim($input) != $this->_lastName) {
            throw new Exception('Undefined expression: ' . var_export($input, 1));
        }

        $this->_map = array_reverse($this->_map);

        return str_replace(
            array_keys($this->_map),
            array_values($this->_map),
            $input
        );
    }

    protected function _getExpressions()
    {
        $expressions = array(
            'FTemplate_Expression_Var',
            'FTemplate_Expression_Constant',
        );

        $return = array();

        foreach ($expressions as $exp) {
            $return[] = new $exp($this);
        }

        return $return;
    }
}