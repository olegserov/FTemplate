<?
class FTemplate_Tag_Block_Cycle_For extends FTemplate_Tag_Base
    implements FTemplate_Tag_ICustom, FTemplate_Tag_IBlock
{
    const T_TAG_OPEN_FOR_SIMPLE = 1;
    const T_TAG_FOR_CLOSE = 2;

    public static function getRegExp()
    {
        return array(
            self::T_TAG_OPEN_FOR_SIMPLE => '/^for (.*?) in (.*?)..(.*?)$/xis',
            self::T_TAG_FOR_CLOSE => '/^\/for$/xis',
        );
    }

    public function getCode()
    {

    }

    public function isEnd()
    {

    }

    public function isStart()
    {

    }

    public function parse(FTemplate_Parser_Tree_Context $context)
    {

    }
}