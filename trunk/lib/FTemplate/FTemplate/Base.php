<?php
abstract class FTemplate_Base
{
    /**
     * Manager
     * @var FTemplate_Manager
     */
    protected $_manager;

    public function __construct(FTemplate_Manager $manager)
    {
        $this->_manager = $manager;
    }
}