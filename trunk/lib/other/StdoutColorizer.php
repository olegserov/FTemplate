<?php
class StdoutColorizer
{
    protected $_options = array(
    	'bold' => 1,
    	'underscore' => 4,
    	'blink' => 5,
    	'reverse' => 7,
    	'conceal' => 8
    );

    protected $_foreground = array(
    	'black' => 30,
    	'red' => 31,
    	'green' => 32,
    	'yellow' => 33,
    	'blue' => 34,
    	'magenta' => 35,
    	'cyan' => 36,
    	'white' => 37
    );

    protected $_background = array(
    	'black' => 40,
    	'red' => 41,
    	'green' => 42,
    	'yellow' => 43,
    	'blue' => 44,
    	'magenta' => 45,
    	'cyan' => 46,
    	'white' => 47
    );

    protected $_styles = array();

    protected $_isSupported = true;

    public function __construct()
    {
        if (
            DIRECTORY_SEPARATOR == '\\'
            || !function_exists('posix_isatty')
            || !@posix_isatty(STDOUT)
        ) {
            $this->_isSupported = false;
        }
    }

    /*
    public function demo()
    {
        foreach ($this->_foreground as $fg => $t) {
            //foreach ($this->_background as $bg => $r) {
                foreach ($this->_options as $opt => $value) {
                	echo $this->colorize(
                	    $fg . '/'  . $opt . "\t",
                	    array (
                	        'fg' => $fg,
                	        //'bg' => $bg,
                	        $opt
                	    )
                	);
                //}
            }

        }
        sleep(4);
    }
*/

    public function setStyle($name, $parameters = array())
    {
        $this->_styles[$name] = $parameters;
    }

    public function colorize($text, $parameters = array())
    {
        if (!$this->_isSupported) {
            return $text;
        }

        if (!is_array($parameters) && isset($this->_styles[$parameters])) {
            $parameters = $this->_styles[$parameters];
        }

        $codes = array();

        if (isset($parameters['fg']) && isset($this->_foreground[$parameters['fg']])) {
            $codes[] = $this->_foreground[$parameters['fg']];
        }

        if (isset($parameters['bg']) && isset($this->_background[$parameters['bg']])) {
            $codes[] = $this->_background[$parameters['bg']];
        }

        foreach ($this->_options as $option => $value) {
            if(isset($parameters[$option]) && $parameters[$option]) {
                $codes[] = $value;
            }
        }

        return "\033[" . implode(';', $codes) . 'm' . $text . "\033[0m";
    }
}