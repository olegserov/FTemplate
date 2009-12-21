<?php
class FTemplate_Cache extends FTemplate_Base
{
    /**
     * Cache driver.
     *  * false - cache disabled;
     *  * null - cache not inited yet;
     * @var FTemplate_Cache_Interface
     */
    protected $_cacheDriver = null;

    /**
     * Sets cache driver
     * @param $driver
     * @return void
     */
    public function setCacheDriver($driver)
    {
        if ($driver == false) {
            $this->_cacheDriver = false;
        }

        if (!($driver instanceof FTemplate_Cache_Interface)) {
            throw new Exception(get_class($driver) . ' is not instance of FTemplate_Cache_Interface');
        }

        if (is_object($driver)) {
            $this->_cacheDriver = $driver;
        } elseif (!class_exists($driver)) {
            throw new Exception($driver . ' not found');
        } else {
            $this->_cacheDriver = new $driver;
        }
    }

    /**
     * Gets cache driver
     * @return FTemplate_Cache_Interface
     */
    public function getCacheDriver()
    {
        // Cache driver is not inited
        if ($this->_cacheDriver === null) {
            $this->_cacheDriver = new FTemplate_Cache_FS();
        }

        return $this->_cacheDriver;
    }

    public function load($skel)
    {
        if ($this->getCacheDriver() && $this->getCacheDriver()->load($skel)) {
            return true;
        }

        return false;
    }

    public function save($skel)
    {
        if ($this->getCacheDriver()) {
             $this->getCacheDriver()->save($skel);
             return true;
        }

        return false;
    }

}