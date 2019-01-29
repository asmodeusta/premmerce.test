<?php

class Page
{

    private static $instance = null;

    private $data = [];
    private $header = APP . '/views/template/header.php';
    private $footer = APP . '/views/template/footer.php';
    private $view;

    public static function go() {
        if(self::$instance===null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct() {

    }

    public function __get($name) {
        $result = null;
        if(isset($this->data[$name])) {
            $result = $this->data[$name];
        } else {
            //$result = '';
        }
        return $result;
    }

    public function __set($name, $value) {
        $this->data[$name] = $value;
    }

    public function setHeader($header) {
        $result = false;
        if(is_file($header)&&substr($header, strlen($header-4),4)===".php") {
            $this->header = $header;
            $result = true;
        }
        return $result;
    }

    public function setFooter($footer) {
        $result = false;
        if(is_file($footer)&&substr($footer, strlen($footer-4),4)===".php") {
            $this->footer = $footer;
            $result = true;
        }
        return $result;
    }

    public function header() {
        include $this->header;
    }

    public function footer() {
        include $this->footer;
    }

}