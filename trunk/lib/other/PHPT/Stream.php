<?php
class PHPT_Stream
{
    protected $_position;
    protected $_content;

    public static function register()
    {
        stream_wrapper_register('phpt', __CLASS__);
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

        $f = tempnam('/non-existence/', 'phpt');
        file_put_contents($f, $parts[1]);

        $code = "\$ft = new FTemplate();\n"
            . $parts[0]
            . "\$ft->assign(get_defined_vars()); echo \$ft->display('$f');";

        return "--TEST--\n" . $file . "\n--FILE--\n" . $code . "\n--EXPECT--\n" . $parts[2];
    }


    public function stream_open($path, $mode, $options, &$opened_path)
    {
        $file = (substr($path, 7));
        echo "$file\n";
        $this->_content = $this->_parseFile($file);

        return true;
    }

    public function stream_read($count)
    {
        $ret = substr($this->_content, $this->_position, $count);
        $this->_position += strlen($ret);
        return $ret;
    }

    public function stream_write($data)
    {
        return false;
    }

    public function stream_tell()
    {
        return $this->_position;
    }

    public function stream_eof()
    {
        return $this->_position >= strlen($this->_content);
    }

    protected function _getPath($file)
    {
        return realpath(substr($file, 7));
    }
    public function url_stat($file)
    {
        $path = $this->_getPath($file);
        if (file_exists($path)) {
            return stat($path);
        } else {
            return false;
        }
    }

    public function stream_seek($offset, $whence)
    {
        switch ($whence) {
            case SEEK_SET:
                if ($offset < strlen($this->_content) && $offset >= 0) {
                     $this->_position = $offset;
                     return true;
                } else {
                     return false;
                }
                break;

            case SEEK_CUR:
                if ($offset >= 0) {
                     $this->_position += $offset;
                     return true;
                } else {
                     return false;
                }
                break;

            case SEEK_END:
                if (strlen($this->_content) + $offset >= 0) {
                     $this->_position = strlen($this->_content) + $offset;
                     return true;
                } else {
                     return false;
                }
                break;

            default:
                return false;
        }
    }
}