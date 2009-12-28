<?php
abstract class FTemplate_Base
{
    /**
     * Manager
     * @var FTemplate_Factory
     */
    protected $_factory;

    public function __construct(FTemplate_Factory $factory)
    {
        $this->_factory = $factory;
        $this->_init();
    }

    protected function _init()
    {

    }

    /**
     * Gets factory
     * @return FTemplate_Factory
     */
    public function getFactory()
    {
        return $this->_factory;
    }
}