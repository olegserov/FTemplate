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

    public function load(FTemplate_Template_Skel $skel)
    {
        if ($this->_cacheDir === null) {
            return false;
        }

        $cacheFilename = $this->_getCacheFilename($skel->getFile());

        // Read from cache with 1 second gap (for safety).
        if (abs(@filemtime($cacheFilename) - $skel->getFileStamp()) <= 1) {
            include_once $cacheFilename;
            return true;
        }

        return false;
    }

    public function save(FTemplate_Template_Skel $skel)
    {

        if ($this->_cacheDir === null) {
            return;
        }
        $cacheFilename = $this->_getCacheFilename($skel->getFile());

        $f = @fopen($cacheFilename, "a+b");
        if (!$f) {
            return;
        }
        flock($f, LOCK_EX);
        ftruncate($f, 0);
        fwrite($f, $skel->getCode());
        fclose($f);

        $old = umask(0);
        @chmod($cacheFilename, 0666);
        umask($old);

        touch($cacheFilename, $skel->getFileStamp());
    }

    private function _getCacheFilename($path)
    {
        return $this->_cacheDir . '/' . urlencode($path) . '.ftcfs.php';
    }
}