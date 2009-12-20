<?php
class PHPT
{

    public function run($file)
    {

        list($code, $expected) = $this->_parseFile($file);

        $got = trim($this->_evalCode($code));
        $expected = $expected === null ? $expected : trim($expected);

        if ($got !== $expected || $expected === null) {
            throw new PHPT_TextDiffException($file, $expected, $got);
        }
    }



    protected function _parseFile($file)
    {
        $code = file_get_contents($file);
        $parts = preg_split('/\s*----+---\s*/', $code, 3);

        if (!isset($parts[0], $parts[1])) {
            throw new Exception("Bad test file $file.");
        }

        if (!isset($parts[2])) {
            $parts[2] = null;
        }

        $f = dirname(tempnam("non-existed", ''))
            . '/'
            . preg_replace('/\W+/', '_', $file)
            . '.tmp';

        file_put_contents($f, $parts[1]);

        $code = "\$ft = new FTemplate();\n"
            . $parts[0]
            . "\$ft->display('$f', get_defined_vars());";

        return array($code, $parts[2]);
    }

    protected function _evalCode($code)
    {
        ob_start();
        try {
            $f = create_function('', $code);
            $f();
        } catch (Exception $e) {
            ob_end_flush();
            throw $e;
        }
        return ob_get_clean();
    }


    protected function _log()
    {
        $args = func_get_args();

        $format = array_shift($args);

        echo vsprintf($format, $args);
    }
}

