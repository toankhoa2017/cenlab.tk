<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends MY_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('mod_nhansu');
    }
    function index() {
        if ($this->session->userdata('ssAccountId')) redirect(site_url().'admin/profile');
        if ($this->input->post('isSent') == 'OK') {
            $params = $this->input->post();
            $this->load->library('curl');
            $this->curl->create($this->_api['account']['login']);
            $this->curl->post(array(
                'username' => $params['username'],
                'password' => $params['password'],
                'project' => _PROJECT_ID
            ));
            $login = json_decode($this->curl->execute());
            if (isset($login->err_code) && $login->err_code == 100) {
                $nhansu = $this->mod_nhansu->_gets($login->user->id);
                $this->session->set_userdata(array(
                    'ssAdminId' => $nhansu['nhansu_id'],
                    'ssAdminFullname' => $nhansu['nhansu_lastname'].' '.$nhansu['nhansu_firstname'],
                    'ssAdminDonvi' => $nhansu['donvi_id'],
                    'ssAdminChucvu' => $nhansu['chucvu_id'],
                    'ssAccountId' => $login->user->id
                ));
                redirect(site_url().'admin/profile');
            }
            else redirect(site_url().'admin/login');
        }
        $this->parser->parse('login');
    }
    function _isCurl(){
        return function_exists('curl_version');
    }
	function clearLicense() {
		$this->session->unset_userdata('ssCheckLicense');
	}
}
