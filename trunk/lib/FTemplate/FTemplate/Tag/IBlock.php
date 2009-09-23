<?php
interface FTemplate_Tag_IBlock extends FTemplate_Tag_ICustom
{
    public function isStart();
    public function isEnd();
}