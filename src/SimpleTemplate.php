<?php

/**
 * A very very simple template engine(?) for PHP.
 *
 * Now just do one thing:
 * Transform `{{$name}}` to `<?php echo htmlspecialchars($name, ENT_QUOTES); ?>`
 *
 * * PHP is already the best template language *
 */
class SimpleTemplate {

    private $tplPath;
    private $content;
    private $vars = array();
    private $varTagPairs = array('{{', '}}');

    /**
     * Construct a instance
     * @param string $tplPath the file path of template
     */
    public function __construct($tplPath) {
        if (!file_exists($tplPath)) {
            throw new Exception("File {$tplPath} not exists");
        }
        $this->tplPath = $tplPath;
    }

    /**
     * Custom the literal pairs of variable tag
     * @param string $left default is `{{`
     * @param string $right default is `}}`
     */
    public function setVarTagPairs($left, $right) {
        $this->varTagPairs = array($left, $right);
    }

    /**
     * Assign a variable to template
     * @param string $name
     * @param mixed $value
     */
    public function assign($name, $value) {
        $this->vars[$name] = $value;
    }

    /**
     * Every row of associated array will be assigned to template
     * @param array $array
     */
    public function assignAll($array) {
        foreach ($array as $name => $value) {
            $this->assign($name, $value);
        }
    }

    /**
     * Render the content of template
     * @param bool $output echo the content if true or return the content if false
     * @return null | string
     */
    public function render($output = true) {
        $this->content = file_get_contents($this->tplPath);
        $this->replaceVarTag();
        return $this->display($output);
    }

    private function replaceVarTag() {
        list($left, $right) = $this->varTagPairs;
        $reg = '/'.$left.'([\s\S]+?)'.$right.'/';
        $replace = '<?php echo htmlspecialchars(${1}, ENT_QUOTES); ?>';
        $this->content = preg_replace($reg, $replace, $this->content);
    }

    private function display($output) {
        extract($this->vars);

        if (!$output) {
            ob_start();
            ob_implicit_flush(0);
        }

        eval('?>' . $this->content);

        if (!$output) {
            $content = ob_get_clean();
            return $content;
        }
    }

}