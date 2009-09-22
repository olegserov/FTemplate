<?php
class FTemplate_Cache_FS implements FTemplate_Cache_Interface
{
    protected $_cacheDir;

    public function __construct()
    {

        $this->_cacheDir = dirname(tempnam('non-existed', '')) . '/' . __CLASS__;

        if (!@is_dir($this->_cacheDir)) {
            $old = umask(0);
            @mkdir($this->_cacheDir, 0777, true);
            umask($old);
        }
    }

    public function load($path, $stamp)
    {
        if ($this->_cacheDir === null || !$stamp) {
            return false;
        }

        $cacheFilename = $this->_getCacheFilename($path);

        // Read from cache with 1 second gap (for safety).
        if (abs(@filemtime($cacheFilename) - $stamp) <= 1) {
            include_once $cacheFilename;
            return true;
        }

        return false;
    }

    public function save($path, $stamp, $content)
    {

        if ($this->_cacheDir === null || !$stamp) {
            return;
        }
        $cacheFilename = $this->_getCacheFilename($path);

        $f = @fopen($cacheFilename, "a+b");
        if (!$f) {
            return;
        }
        flock($f, LOCK_EX);
        ftruncate($f, 0);
        fwrite($f, $content);
        fclose($f);
        $old = umask(0);
        @chmod($cacheFilename, 0666);
        umask($old);
        touch($cacheFilename, $stamp);
    }

    private function _getCacheFilename($path)
    {
        return $this->_cacheDir . '/' . urlencode($path) . '.ftcfs.php';
    }
}