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
                $reg_exps = $exp->getRegExp();

                if (!is_array($reg_exps)) {
                    $reg_exps = (array) $reg_exps;
                }

                foreach ($reg_exps as $reg_exp) {
                    $reg_exp = str_replace('T_EXPRESSION', '(?:\\~[0-9]+\\~)', $reg_exp);

                    $input = preg_replace_callback(
                        '/' . $reg_exp . '/x',
                        array($this, '_parseToken'),
                        $input,
                        -1,
                        $count
                    );

                    if ($count) {
                        continue 3;
                    }
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
            'FTemplate_Expression_StringConstant',
            'FTemplate_Expression_Var',
            'FTemplate_Expression_Operators',
            'FTemplate_Expression_Constant',
        );

        $return = array();

        foreach ($expressions as $exp) {
            $return[] = new $exp($this);
        }

        return $return;
    }
}