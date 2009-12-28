<?php
class FTemplate extends FTemplate_Factory
{
    public function display($template, $args, $name = 'main')
    {
        $this->_loadFile($template)
            ->createObject($this->createEnvironment($args))
            ->show($name);
    }

    protected function _loadFile($origFile)
    {
        $skel = new FTemplate_Template_Skel($origFile);

        if (
            class_exists($skel->getClassName(), false)
            || $this->getCache()->load($skel)
        ) {
            return $skel;
        }

        $this->getParser()->parse($skel);

        $this->getCompiler()->compile($skel);

        $this->getCache()->save($skel);

        $skel->evalCode();

        return $skel;
    }
}
