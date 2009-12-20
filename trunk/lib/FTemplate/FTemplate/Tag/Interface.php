<?php
interface FTemplate_Tag_Interface
{
    public function __construct(FTemplate_Token_Base $token, $key = null);

    public function getCode();
}