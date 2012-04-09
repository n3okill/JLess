<?php

namespace JLess\vendors;

/**
 * JLessAgar class file.
 * @author João Parreira <joaofrparreira@gmail.com>
 * @copyright Copyright &copy; 2012, João Parreira
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * This extension is inspired in LessCompiler made by Christoffer Niska <ChristofferNiska@gmail.com>
 */


/**
 * Wraper to the Agar parser
 * https://github.com/agar/less.php
 */
\Yii::setPathOfAlias('Less', dirname(__FILE__) . '/less/agar/lib/Less');

class JLessAgar extends \JLess\JLessCompiler {
    /*
     * Default options to the parser
     */

    private $_options = array(
        'compress' => false,
        'debug' => false,
    );

    /*
     * Parser constructor for agar
     * @param array options to the compiler 
     */

    public function __construct($options) {
        parent::__construct($options);
        $this->options['compiler'] = \CMap::mergeArray($this->_options, $this->options['compiler']);
        $this->compiler = new \Less\Parser();
        foreach ($this->options['compiler'] as $key => $option) {
            $this->compiler->getEnvironment()->{$key} = $option;
        }
    }

    /*
     * Parse the file with agar parser
     */

    public function compileFile() {
        if (parent::compileFile()) {
            try {
                $this->compiler->parseFile($this->originalFullPath);
            } catch (\Less\Exception\ParserException $ex) {
                throw new CException(__CLASS__ . ': Failed to parse less file. "' . $ex->getMessage() . '".');
            }
            file_put_contents($this->destinationFullPath, $this->compiler->getCss());
        }
    }

    /*
     * Parse the less string block to css with agar
     */

    public function compileString() {
        parent::compileString();
        try {
            $this->compiler->parse($this->options['string']);
            $this->cssString = $this->compiler->getCss();
        } catch (\Less\Exception\ParserException $ex) {
            throw new CException(__CLASS__ . ': Failed to parse less string. "' . $ex->getMessage() . '".');
        }
    }

}

?>
