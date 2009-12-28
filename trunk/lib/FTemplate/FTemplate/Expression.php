<?php
class FTemplate_Expression extends FTemplate_Base
{
    protected $_mapReplace;

    protected $_mapTypes;

    protected $_lastName;

    protected $_key;

    protected $_expressions = array();

    protected $_expressionsRegExp = array();

    protected function _reset()
    {
        $this->_mapTypes = $this->_mapReplace = array();
        $this->_lastName = $this->_key = null;
    }

    protected function _parseExpression(array $matches)
    {
        $this->_lastName = '~' . count($this->_mapReplace) . '~';

        $this->_mapReplace[$this->_lastName]
            = $this->_expressions[$this->_key]->compile($matches);

        $this->_mapTypes[$this->_lastName] = $this->_key;

        return $this->_lastName;
    }

    public function parse($input)
    {
        $this->_reset();

        //$input = $token->getInput();

        if (empty($input)) {
            throw new Exception('Empty input');
        }

        $count = 0;

        do {
            foreach ($this->_expressionsRegExp as $level) {
                foreach ($level as $expression => $reg_exps) {
                    $this->_key = $expression;

                    $input = preg_replace_callback(
                        $reg_exps,
                        array($this, '_parseExpression'),
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
            throw new Exception('Undefined expression: ' . $input);
        }

        $this->_mapReplace = array_reverse($this->_mapReplace);

        return str_replace(
            array_keys($this->_mapReplace),
            array_values($this->_mapReplace),
            $input
        );
    }

    public function prepareRegExp($regExp)
    {
        return '{' . str_replace('T_EXP', '(?:\\~[0-9]+\\~)', $regExp) . '}isx';
    }

    public function __construct()
    {
        $expressions = array(
            'FTemplate_Expression_Argument_StringConstant',
            'FTemplate_Expression_Argument_Numeric',
            'FTemplate_Expression_Argument_Var',
            'FTemplate_Expression_Argument_RoundBracket',
            'FTemplate_Expression_Operator_Binary_Operators',
            'FTemplate_Expression_Argument_Constant',
        );

        foreach ($expressions as $exp) {
            $this->addExpression(new $exp);
        }
     }

    public function addExpression(FTemplate_Expression_Interface $expression, $priority = 0)
    {
        $this->_expressions[
            get_class($expression)
        ] = $expression;

        foreach((array) $expression->getRegExp() as $exp) {
            $this->_expressionsRegExp[$priority][get_class($expression)][]
                = $this->prepareRegExp($exp);
        }

        ksort($this->_expressionsRegExp);

        $this->_expressionsRegExp = array_reverse($this->_expressionsRegExp);
    }

    public function compileGlobalRegExp()
    {
        return "
            (\(
                (
                    (?>[^()'\"]*)             # All, except: \", ', (, )
                    | \"([^\"\\\\]*|\\\\.)*\" # Double quoted string
                    | '([^'\\\\]*|\\\\.)*'    # Single quoted string
                    | (?1)*                   # Recursive
                )*
            \))
        ";
    }
}