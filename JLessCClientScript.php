<?php

/**
 * LessCompiler class file.
 * @author João Parreira <joaofrparreira@gmail.com>
 * @copyright Copyright &copy; 2012, João Parreira
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * This extension is inspired in LessCompiler made by 
 * Christoffer Niska <ChristofferNiska@gmail.com>
 * 
 * Created to easily manage less files in Yii and to permit more extensions to 
 * be implemented like sass or scss
 */

/**
 * JLessCClientScript extends CClientScript and manages Less files
 */

Yii::setPathOfAlias('JLess', dirname(__FILE__) . DIRECTORY_SEPARATOR);

class JLessCClientScript extends CClientScript {

    /**
     * @var array Default JLess options
     */

    //Default options to run the parser
    private $_jLessOptions = array(
        'vendor' => 'leafo',
        'leafo' => array(
            'class' => 'JLessLeafo'
        ),
        'agar' => array(
            'class' => 'JLessAgar'
        ),
    );
    public $jLessOptions;

    /**
     * Registers a Css file based in a less file
     * @param string $url URL of the Less file
     * @param string $media media that the Less file should be applied to. If empty, it means all media types.
     * @param string $path path to save css file if diferent from the path defined in options 
     * (Ex: Yii::app()->theme->baseUrl.'/css/' - to save in css folder under current theme)
     * Note: Folder must be writable
     * @return CClientScript the CClientScript object itself (to support method chaining, available since version 1.1.5).
     */
    public function registerLessFile($url, $media = '', $path = '') {
        $this->jLessOptions['type'] = 'file';
        $this->jLessOptions["url"] = $url;
        $this->jLessOptions["path"] = $path;
        $this->jLessOptions = CMap::mergeArray($this->_jLessOptions, $this->jLessOptions);
        $classa = "JLess\\vendors\\" . $this->jLessOptions[$this->jLessOptions['vendor']]['class'];
        $compiler = new $classa($this->jLessOptions);
        $compiler->compileFile();
        return parent::registerCssFile($compiler->getCss(), $media);
    }

    /**
     * Registers a piece of Less code.
     * @param string $id ID that uniquely identifies this piece of Less code
     * @param string $less the Less code
     * @param string $media media that the CSS code should be applied to. If empty, it means all media types.
     * @return CClientScript the CClientScript object itself (to support method chaining, available since version 1.1.5).
     */
    public function registerLess($id, $less, $media = '') {
        $this->jLessOptions['type'] = 'string';
        $this->jLessOptions['string'] = $less;
        $this->jLessOptions = CMap::mergeArray($this->_jLessOptions, $this->jLessOptions);
        $classa = "JLess\\vendors\\" . $this->jLessOptions[$this->jLessOptions['vendor']]['class'];
        $compiler = new $classa($this->jLessOptions);
        $compiler->compileString();
        return parent::registerCss($id, $compiler->getCss(), $media);
    }
    /*
     * TODO: Extend extension to manage sass and scss files
     */
}

?>
