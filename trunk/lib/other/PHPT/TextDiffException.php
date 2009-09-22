<?php
class PHPT_TextDiffException extends Exception implements PHPT_IException
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

    public function __construct($file, $expected, $got)
    {

        $this->_file = $file;
        $this->_expected = $expected;
        $this->_got = $got;
    }

    public function getBody()
    {
        if ($this->_expected === null) {
            file_put_contents($this->_file, "\n\n---------------\n\n" . $this->_got, FILE_APPEND);
            return "Test appended";
        } else {
            return $this->_diff($this->_expected, $this->_got);
        }
    }

    protected function _diff($expected, $actual)
    {
        $expectedFile = tempnam('/tmp', 'expected');
        file_put_contents($expectedFile, $expected);

        $actualFile = tempnam('/tmp', 'actual');
        file_put_contents($actualFile, $actual);

        $buffer = shell_exec(
          sprintf(
            'diff -u %s %s',
            escapeshellarg($expectedFile),
            escapeshellarg($actualFile)
          )
        );

        unlink($expectedFile);
        unlink($actualFile);

        if (!empty($buffer)) {
            $buffer = explode("\n", $buffer);

            $buffer[0] = "--- Expected";
            $buffer[1] = "+++ Actual\n";


            $buffer = implode("\n", $buffer);
        }


        return $buffer;
    }
}