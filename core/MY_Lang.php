<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class MY_Lang extends MX_Lang {
    public function __construct() {
        parent::__construct();
    }
    function line_ext($line = '') {
        return ($line == '' OR ! isset($this->language[$line])) ? $line.'_txt' : $this->language[$line];
    }
    function getLang() {
        return $this->language;
    }
}
?>