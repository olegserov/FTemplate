<?php
interface FTemplate_Cache_Interface
{
    public function load(FTemplate_Template_Skel $skel);

    public function save(FTemplate_Template_Skel $skel);
}