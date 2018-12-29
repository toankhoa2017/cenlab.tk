<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends MY_Controller {
    function __construct() {
        parent::__construct();
		$this->load->model('mod_customer');
    }
    function index() {
		if ($this->session->userdata('ssCustomerId')) redirect(site_url().'customer/ketqua/danhsachketqua');
        if ($this->input->post('isSent') == 'OK') {
            $params = $this->input->post();
			$user = $this->mod_customer->_login(array(
				'user' => $params['username'],
				'password' => $params['password']
			));
			if ($user) {
				$congty = $this->mod_customer->_getCongtyofContact($user['contact_id']);
				$ssCongtyArr = array();
				foreach ($congty as $ct) {
					$ssCongtyArr[] = $ct['id'];
				}
				$this->session->set_userdata(array(
					'ssCustomerId' => $user['contact_id'],
					'ssCongtyArr' => json_encode($ssCongtyArr)
				));
				redirect(site_url().'customer/ketqua/danhsachketqua');
			}
            redirect(site_url().'customer/login');
        }
		$this->parser->parse('login');
    }
}