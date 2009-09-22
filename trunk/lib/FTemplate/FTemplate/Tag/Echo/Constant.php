<?php
class FTemplate_Tag_Echo_Constant extends FTemplate_Tag_Base
{
    public function getCode()
    {
        return '?>'
            . str_replace('<?', '<<??>?', $this->_input)
            . '<?';
    }
}