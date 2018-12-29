<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Cautructailieu extends MY_Controller {
    function __construct() {
        parent::__construct();
         $this->load->model('mod_cautructailieu');
    }
}
