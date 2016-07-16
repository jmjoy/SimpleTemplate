class SimpleTemplate {

    private $tplPath;
    private $content;
    private $vars = array();

    private $varTagPairs = array('{{', '}}');

    public function __construct($tplPath) {
        if (!file_exists()) {
            throw new Exception("File {$tplPath} not exists");
        }
        $this->tplPath = $tplPath;
    }

    public function setVarTagPairs($left, $right) {
        $this->varTagPairs = array($left, $right);
    }

    public function assign($name, $value) {
        $this->vars[$name] = $value;
    }

    public function assignAll($array) {
        foreach ($array as $name => $value) {
            $this->assign($name, $value);
        }
    }

    public function display() {
        $this->content = file_get_contents($this->tplPath);
        $this->replaceVarTag();

        extract($this->vars);
        require 'data:text/plain,' . urlencode($this->content);
    }

    private function replaceVarTag() {
        list($left, $right) = $this->varTagPairs;
        $reg = '/'.$left.'([\s\S]+?)'.$right.'/';
        $replace = '<?php echo htmlspecialchars(${1}, ENT_QUOTES); ?>';
        $this->content = preg_replace($reg, $replace, $this->content);
    }

}