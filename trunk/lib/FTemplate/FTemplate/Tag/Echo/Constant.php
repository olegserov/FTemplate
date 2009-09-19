<?php
class FTemplate_Tag_Echo_Constant extends FTemplate_Tag
{
    public function getCode()
    {
        return '?>'
            . ($this->_input[0] == "\r" || $this->_input[0] == "\n"? " " : null)
            . str_replace('<?', '<<??>?', $this->_input)
            . '<?';
    }
}