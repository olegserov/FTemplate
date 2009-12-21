<?php
class FTemplate extends FTemplate_Manager
{
    public function display($template, $args, $name = 'main')
    {
        $this->_loadFile($template)->createObject($args)->show($name);
    }

    protected function _loadFile($origFile)
    {
        $skel = new FTemplate_Template_Skel($origFile);

        if (
            class_exists($skel->getClass(), false)
            || $this->getCache()->load($skel)
        ) {
            return $skel;
        }

        $this->getParser()->parse($skel);

        $this->getCompiler()->compile($skel);

        $this->getCache()->save($skel);

        return $skel;
    }
}
