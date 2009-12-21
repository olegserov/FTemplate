<?php
class FTemplate_Compiler_Parser_Streamer
{
    /**
     * Parsed data
     * @var array
     */
    protected $_parsed;

    /**
     * Left-to-parse string
     * @var string
     */
    protected $_left;

    /**
     * Currently matched result
     * @var array
     */
    protected $_matched;

    /**
     * Next matched result
     * @var string
     */
    protected $_nextMatched;

    /**
     * Create strign streamer
     * @param $string
     */
    public function __construct($string)
    {
        $this->_left = ltrim($string);
    }

    /**
     * Expect pattern in straem
     * @throws FTemplate_Compiler_Parser_Streamer_Exception if string not found
     * @param $regex pattern
     * @param $named named pattern
     * @return FTemplate_Compiler_Parser_Streamer
     */
    public function expect($regex, $named = null)
    {
        if ($this->test($regex)) {
            $this->movePointer($named);
        } else {
            $this->errorNotExpected($regex);
        }

        return $this;
    }

    /**
     * Assert that next part of string is matched with pattern
     * @param $regex pattern
     * @return boolean
     */
    public function test($regex)
    {
        $matched = null;

        $return = preg_match("{^(?:$regex)}six", $this->_left, $matched);

        if ($return) {
            $this->_nextMatched = $matched;
            return true;
        }

        return false;
    }

    /**
     * If is end of string?
     * @return boolean
     */
    public function isEnd()
    {
        return strlen($this->_left) == 0;
    }

    /**
     * Throws error
     * @param $regEx
     * @return void
     */
    public function errorNotExpected($what)
    {
        throw new FTemplate_Compiler_Parser_Streamer_Exception(
            "Expected: $what got: $this->_left"
        );
    }

    /**
     * Moves pointer to next
     * @param $named
     * @return void
     */
    public function movePointer($named = null)
    {
        if ($this->_nextMatched === null) {
            throw new FTemplate_Compiler_Parser_Streamer_Exception(
                'Nothing parsed for next!'
            );
        }
        if ($named === null) {
            $this->_parsed[] = $this->_nextMatched;
        } else {
            $this->_parsed[$named] = $this->_nextMatched;
        }

        $this->_matched = $this->_nextMatched;

        $this->_left = substr($this->_left, strlen($this->_nextMatched[0]));
        $this->_left = ltrim($this->_left);

        $this->_nextMatched = null;
    }

    /**
     * Gets named match
     * @param $name
     * @return array
     */
    public function getNamed($name)
    {
        return @$this->_parsed[$name];
    }

    /**
     * Gets current matched
     * @return array
     */
    public function getCurrent()
    {
        return $this->_matched;
    }

    /**
     * Gets next matched
     * @return array
     */
    public function getNext()
    {
        return $this->_nextMatched;
    }
}