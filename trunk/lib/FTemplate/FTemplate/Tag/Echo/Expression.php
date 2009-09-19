<?php
class FTemplate_Tag_Echo_Expression extends FTemplate_Tag implements FTemplate_Tag_ICustom
{

    protected $_map;
    protected $_lastName;

    protected $_expression;

    public static function getRegEx()
    {
        return '.*';
    }

    public function getCode()
    {
        return sprintf("echo %s;", $this->_input);
    }

    protected function _parseToken(array $matches)
    {
        $this->_lastName = "\0\0" . count($this->_map) . "\0\0";
        $this->_map[$this->_lastName] = $this->_expression->parse($matches);
        return $this->_lastName;
    }

    public function parse($context)
    {
        if (empty($this->_input)) {
            throw new Exception('Empty input');
        }

        $count = 0;

        do {
            foreach ($this->_getExpressions() as $exp) {
                $this->_expression = $exp;
                $this->_input = preg_replace_callback(
                    '/' . $exp->getRegExp() . '/six',
                    array($this, '_parseToken'),
                    $this->_input,
                    -1,
                    $count
                );

                if ($count) {
                    continue;
                }
            }
        } while ($count);

        if ($this->_input != $this->_lastName) {
            throw new Exception('Undefined expression: ' . $this->_input);
        }

        $this->_input = str_replace(
            array_keys($this->_map),
            array_values($this->_map),
            $this->_input
        );
    }

    protected function _getExpressions()
    {
        $expressions = array(
            'FTemplate_Expression_Constant'
        );

        $return = array();

        foreach ($expressions as $exp) {
            $return[] = new $exp($this);
        }

        return $return;
    }
}