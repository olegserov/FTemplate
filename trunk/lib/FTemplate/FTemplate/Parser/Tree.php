<?php
class FTemplate_Parser_Tree extends FTemplate_Parser_Base
{
    protected $_tree;

    public function get($tags)
    {
        $this->_tree = array();

        $this->_compileTree($tags, 'main');

        return $this->_tree;
    }

    protected function _compileTree($tags, $template)
    {
        foreach ($tags as $tag) {
            $this->_tree[$template][] = $tag;
        }
    }
}