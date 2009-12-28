<?php
class FTemplate_Compiler extends FTemplate_Base
{
    protected $_skel;

    public function compile(FTemplate_Template_Skel $skel)
    {
        ob_start();
        try {
            $this->_echoHead($skel);

            $templates = $skel->context->getTemplates();

            foreach($templates as $template) {
                $this->_echoTemplate($template);
            }

            echo '}';
        } catch (Exception $e) {
            $skel->setCode(ob_get_clean());
            throw $e;
        }

        $skel->setCode(ob_get_clean());
    }

    protected function _echoTemplate(FTemplate_Compiler_Context_Template $template)
    {
        echo sprintf(
            'public function %s(array $__args = null){?>',
            $template->getQuotedName()
        );

        $template->echoRaw();

        echo '<?}';
    }

    protected function _echoHead($skel)
    {
        echo sprintf(
            '<?class %s extends FTemplate_Template_Base {',
            $skel->getClassName()
        );

        $templates = array();

        foreach ($skel->context->getTemplates() as $template) {
            $templates[$template->getName()] = $template->getQuotedName();
        }
        echo sprintf(
            'protected function _getTemplates(){return %s;}',
            var_export($templates, 1)
        );
    }
}