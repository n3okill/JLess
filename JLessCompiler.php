<?php

namespace JLess;

/**
 * JLessCompiler class file.
 * @author João Parreira <joaofrparreira@gmail.com>
 * @copyright Copyright &copy; 2012, João Parreira
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * This extension is inspired in LessCompiler made by Christoffer Niska <ChristofferNiska@gmail.com>
 */

/**
 * JLessCompiler Parent class for compilers
 */
class JLessCompiler {

    protected $originalPath = '';       //Original path defined by user
    protected $originalFullPath = '';   //Full path for original file
    protected $destinationPath;         //Destination path to resultant file
    protected $destinationFullPath;     //Full path to resultant file
    protected $cssString;               //String with css code parsed
    protected $compiler;                //Compiler Handler
    private $_options = array(//Default options to all compilers
        'forceCompile' => true,
    );
    protected $options;

    public function __construct($options) {
        $this->options = \CMap::mergeArray($this->_options, $options);
        //Detects if is loaded a less file and define the path's necessary
        if ($this->options['type'] == 'file') {
            $this->originalPath = $this->options['url'];
            $this->originalFullPath = dirname(\Yii::getPathOfAlias('webroot')) . $this->originalPath;
            $nomeFicheiro = pathinfo($this->originalPath, PATHINFO_FILENAME);
            if ($this->options['path'] != "")
                $this->destinationPath = $this->options["path"];
            else {
                if (!isset($this->options["destination"]))
                    throw new \CException("Destination folder not chosen");
                else
                    $this->destinationPath = \Yii::app()->getRequest()->getBaseUrl() . $this->options["destination"] . '/' . $nomeFicheiro . '.css';
            }
            $this->destinationFullPath = dirname(\Yii::getPathOfAlias('webroot')) . $this->destinationPath;
        }
    }

    /*
     * Parent function for file parser
     */

    protected function compileFile() {
        return ($this->options['forceCompile'] || $this->hasChanges());
    }

    /*
     * Parent function for string parser
     */

    protected function compileString() {
        
    }

    /**
     * Returns if the file has changes or not by the modified time
     * @return boolean the result
     */
    private function hasChanges() {
        if (!file_exists($this->originalFullPath)) //If original file don't exist it should do nothing in here
            throw new CException(__CLASS__ . ': Failed to compile less file. Source path does not exist.');
        if (!file_exists($this->destinationFullPath))   //If file don't exist it should be force compiled
            return true;
        else {
            $statOriginal = stat($this->originalFullPath);
            $statDestination = stat($this->destinationFullPath);
            if ($statOriginal['mtime'] > $statDestination['mtime']) //If Original file is more recent than Destination file then compile
                return true;
        }
    }

    /*
     * Returns the path to the resultant css file or the css string
     */

    public function getCss() {
        switch ($this->options['type']) {
            case 'file':
                return $this->destinationPath;
            case 'string':
                return $this->cssString;
            default:
                throw new CException(__CLASS__ . ': Invalid type option.');
                break;
        }
    }

}

?>
