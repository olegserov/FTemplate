<?php
class FTemplate_Test_Parser_StreamerTest extends PHPUnit_Framework_TestCase
{
    protected function _createStreamer($text)
    {
        return new FTemplate_Parser_Streamer($text);
    }

    public function testTestTrue()
    {
        $pairs = array(
            array('Hello world!', 'Hello'),
            array('PHP is power!', 'PHP'),
            array(' World', 'World'),
            array('test space', 'test\ space'),
            array('test space', 'te(st\ sp)ace'),
        );

        foreach ($pairs as $pair) {
            $this->assertTrue(
                $this->_createStreamer($pair[0])->test($pair[1]),
                var_export($pair, 1)
            );
        }
    }

    public function testTestFalse()
    {
        $pairs = array(
            array('Hello world!', 'Hello{2}'),
            array('PHP is power!', 'PH{3}P'),
        );

        foreach ($pairs as $pair) {
            $this->assertFalse(
                $this->_createStreamer($pair[0])->test($pair[1]),
                var_export($pair, 1)
            );
        }
    }

	public function testSimple()
	{
        $stream = $this->_createStreamer('Hello World!');

        $this->assertTrue(
            $stream->expect('hello')
                ->expect('world')
                ->expect('\!')
                ->isEnd()
        );
	}

	public function testExpectNamed()
	{
	    $streamer = $this->_createStreamer('Now is 2010!')->expect('now')->expect('is');

	    $streamer->expect('\d+', 'year');

	    $this->assertEquals((array) '2010', $streamer->getNamed('year'));
	}

	public function testEnd()
	{
	    $streamer = $this->_createStreamer('Now is 2010!')->expect('now')->expect('is');

	    $streamer->expect('\d+', 'year')->expect('\!')->expect('$');

	    $this->assertEquals((array) '2010', $streamer->getNamed('year'));
	}

    /**
     *
     * @expectedException FTemplate_Parser_Streamer_Exception
     */
	public function testEndUnexpected()
	{
        $this->_createStreamer('Now is 2010!')->expect('$');
	}



	/**
	 *
	 * @expectedException FTemplate_Parser_Streamer_Exception
	 */
	public function testUnExpecred()
	{
	    $this->_createStreamer('Hello world!')->expect('hello')->expect('oleg');
	}
}