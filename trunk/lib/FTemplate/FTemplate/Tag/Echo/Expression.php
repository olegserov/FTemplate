<?php
class FTemplate_Tag_Echo_Expression extends FTemplate_Tag implements FTemplate_Tag_ICustom
{

    public static function getRegEx()
    {
        return '.*';
    }

    public function getCode()
    {
        return 'echo "wtf?"';
    }
}