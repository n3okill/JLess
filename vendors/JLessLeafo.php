<?php

namespace JLess\vendors;

/**
 * JLessLeafo class file.
 * @author João Parreira <joaofrparreira@gmail.com>
 * @copyright Copyright &copy; 2012, João Parreira
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * This extension is inspired in LessCompiler made by Christoffer Niska <ChristofferNiska@gmail.com>
 */

/**
 * Wraper to the Leafo parser
 * http://leafo.net/lessphp/
 */
class JLessLeafo extends \JLess\JLessCompiler {

    
    private $_options = array();    //Default options to the parser

    /*
     * Parser constructor for leafo
     * @param array options to the compiler 
     */
    public function __construct($options) {
        parent::__construct($options);
        $this->options['compiler'] = \CMap::mergeArray($this->_options, $this->options['compiler']);
        require_once dirname(__FILE__) . '/less/leafo/lessc.inc.php';
        if ($this->options['type'] == 'file')
            $this->compiler = new \lessc($this->originalFullPath, $options);
        else
            $this->compiler = new \lessc();
        foreach ($this->options['compiler'] as $key => $option) {
            $this->compiler->{$key} = $option;
        }
    }
    
    /*
     * Parse the file with leafo parser
     */
    public function compileFile() {
        if (parent::compileFile()) {
            try {
                file_put_contents($this->destinationFullPath, $this->compiler->parse());
            } catch (\Exception $ex) {
                throw new \CException(__CLASS__ . ': Failed to parse less file. "' . $ex->getMessage() . '".');
            }
        }
    }
    
    /*
     * Parse the less string block to css with leafo
     */
    public function compileString() {
        parent::compileString();
        try {
            $this->cssString = $this->compiler->parse($this->options['string']);
        } catch (\Exception $ex) {
            throw new \CException(__CLASS__ . ': Failed to parse less file. "' . $ex->getMessage() . '".');
        }
    }

}

?>
