<?php
class FTemplate_Compiler
{
    public function compile($tree, $className)
    {
        ob_start();

        $this->_echoHeader($className);

        try {
            foreach ($tree as $method => $list) {
                $this->_echoMethod($method, $list);
            }
        } catch (Exception $e) {
            ob_flush();
            throw $e;
        }

        $this->_echoFooter();

        return ob_get_clean();
    }

    protected function _echoMethod($method, $list)
    {
        $this->_echoBlock($method);

        foreach ($list as $node) {
            echo $node->getCode();
        }

        $this->_echoEndBlock();
    }

    protected function _echoHeader($className)
    {
        echo 'class ', $className, '{';
    }

    protected function _echoBlock($name = 'main')
    {
        echo 'public function ' . $name . '(){';
    }

    protected function _echoEndBlock()
    {
        echo '}';
    }

    protected function _echoFooter()
    {
        echo '}';
    }
}