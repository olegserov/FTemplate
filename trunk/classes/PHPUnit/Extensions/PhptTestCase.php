<?php

PHPUnit_Util_Filter::addFileToFilter(__FILE__, 'PHPUNIT');

/**
 * Wrapper to run .phpt test cases.
 *
 * @category   Testing
 * @package    PHPUnit
 * @author     Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright  2002-2009 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.4.0
 * @link       http://www.phpunit.de/
 * @since      Class available since Release 3.1.4
 */
class PHPUnit_Extensions_PhptTestCase implements PHPUnit_Framework_SelfDescribing, PHPUnit_Framework_Test
{
    /**
     * The filename of the .phpt file.
     *
     * @var    string
     */
    protected $filename;


    /**
     * Constructs a test case with the given filename.
     *
     * @param  string $filename
     * @param  array  $options
     */
    public function __construct($filename, array $options = array())
    {
        if (!is_string($filename)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        if (!is_file($filename)) {
            throw new PHPUnit_Framework_Exception(
              sprintf(
                'File "%s" does not exist.',
                $filename
              )
            );
        }

        $this->filename = $filename;
    }

    /**
     * Counts the number of test cases executed by run(TestResult result).
     *
     * @return integer
     */
    public function count()
    {
        return 1;
    }

    /**
     * Runs a test and collects its result in a TestResult instance.
     *
     * @param  PHPUnit_Framework_TestResult $result
     * @param  array                        $options
     * @return PHPUnit_Framework_TestResult
     */
    public function run(PHPUnit_Framework_TestResult $result = NULL, array $options = array())
    {
        if ($result === NULL) {
            $result = new PHPUnit_Framework_TestResult;
        }

        $result->run(
            new PHPUnit_Extensions_PhptTestCaseStub($this->filename)
        );

        return $result;
    }

    /**
     * Returns the name of the test case.
     *
     * @return string
     */
    public function getName()
    {
        return $this->toString();
    }

    protected function _runTest()
    {

    }

    /**
     * Returns a string representation of the test case.
     *
     * @return string
     */
    public function toString()
    {
        return $this->filename;
    }
}

class PHPUnit_Extensions_PhptTestCaseStub
    extends PHPUnit_Framework_TestCase
{
    private $_file;

    public function __construct($file)
    {
        $this->_file = $file;
    }

    public function runBare()
    {
        try {
            $phpt = new PHPT();
            $phpt->run($this->_file);
        } catch (PHPT_TextDiffException $e) {
            $this->assertEquals($e->getExpected(), $e->getGot(), $this->_file);
        }
    }

    public function run(PHPUnit_Framework_TestResult $result = NULL)
    {
        throw new Exception('Do not call!');
    }

    public function count() {
        return 1;
    }

    public function getName()
    {
        return $this->_file;
    }
}