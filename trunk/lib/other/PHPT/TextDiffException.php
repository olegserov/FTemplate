<?php
class PHPT_TextDiffException extends Exception
{
    protected $_file;
    protected $_expected;
    protected $_got;

    public function getTitle()
    {
        if ($this->_expected === null) {
            return 'Nothing to expect';
        } else {
            return 'Output does not match';
        }
    }

    public function getGot()
    {
        return $this->_got;
    }

    public function getExpected()
    {
        return $this->_expected;
    }

    public function __construct($file, $expected, $got)
    {

        $this->_file = $file;
        $this->_expected = $expected;
        $this->_got = $got;

        if ($this->_expected === null) {
            file_put_contents($this->_file, "\n\n---------------\n\n" . $this->_got, FILE_APPEND);
            throw new Exception("Test appended");
        }
    }
}