<?php
class PHPT
{
    protected $_paths = array();

    protected $_filter = '*';

    protected $_tests;

    protected $_fails = array();

    protected $_timer = 0;

    public function setPath($path)
    {
        array_push($this->_paths, $path);
    }

    public function setFilter($filter)
    {
        $this->_filter = $filter;;
    }

    public function run()
    {
        $this->_log("Path: %s;\n", join(";\n\t", $this->_paths));
        $this->_log("Filter: %s;\n", $this->_filter);

        $files = $this->_getFileList();

        $this->_log("File list:\n\t%s\n\n", join("\n\t", $files));
        $this->_log("Files: %d\n", count($files));

        $this->_line();

        $this->_log("\nRunning:\n");

        $ok = 0;

        foreach ($files as $file) {
            $res = $this->_runTest($file);
            if ($res) {
                $ok ++;
                $this->_log('.');
            } else {
                $this->_log('F');
            }
        }

        $this->_log("\n\n");

        $this->_showFails();
        $this->_showTime($ok);



    }

    protected function _line()
    {
        $this->_log("\n%s\n\n", str_repeat('-', 80));
    }

    protected function _showTime($n)
    {
        $this->_log(
            "Total: %0.04f; Avg: %0.04f;\n",
            $this->_timer,
            $this->_timer / $n
        );
    }
    protected function _showFails()
    {
        $this->_line();

        $i = 0;
        foreach ($this->_fails as $file => $e) {
            $this->_log("%d. Fail: %s\n", ++$i, $file);

            if ($e instanceof PHPT_IException) {
                $this->_log("Msg: %s\n", $e->getTitle());
                $this->_log("Error:\n%s\n\n\n", $e->getBody());
            } else {
                $this->_log("Msg: %s\n", $e->getMessage());
                $this->_log("Trace: %s\n\n\n", $e->getTraceAsString());
            }
        }

        $this->_log("Errors: %d\n\n", count($this->_fails));
    }

    protected function _runTest($file)
    {
        try {
            list($code, $expected) = $this->_parseFile($file);

            $got = trim($this->_evalCode($code));
            $expected = $expected === null ? $expected : trim($expected);

            if ($got !== $expected || $expected === null) {
                throw new PHPT_TextDiffException($file, $expected, $got);
            }

            return true;
        } catch (Exception $e) {
            $this->_fails[$file] = $e;
        }

        return false;
    }

    private function _tempnam()
    {
        // Seems tempnam() shrinks prefix by 3 characters...
        $tmp = tempnam("non-existed", '');
        $name = dirname($tmp) . '/' . basename($tmp);
        @unlink($tmp);
        return $name;
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

        $f = $this->_tempnam();
        file_put_contents($f, $parts[1]);

        $code = "\$ft = new FTemplate();\n"
            . $parts[0]
            . "\$ft->assign(get_defined_vars()); echo \$ft->display('$f');";

        return array($code, $parts[2]);
    }

    protected function _evalCode($code)
    {
        ob_start();
        try {

            $f = create_function('', $code);

            $t = microtime(true);
            $f();
            $this->_timer = microtime(true) - $t + $this->_timer;
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

    protected function _compileFilter($filter)
    {
        $filter = str_replace('**', '.*', $filter);
        $filter = str_replace('*', '[^\/]*', $filter);

        $filter = preg_split('/\s+/', $filter);

        $filter = array_map('trim', $filter);

        $filter = join('|', $filter);
        return '/' . $filter . '/si';
    }

    protected function _getFileList()
    {
        $list = array();

        $filter = $this->_compileFilter($this->_filter);

        foreach ($this->_paths as $dir) {
            $itr = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(
                    realpath($dir . '/')
                )
            );
            foreach ($itr as $file) {
                if (!is_file($file) || !preg_match($filter, $file) && !preg_match($filter, basename($file))) {
                    continue;
                }
                $list[] = (string) $file;
            }
        }

        array_unique($list);

        return $list;
    }
}

