<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends ADMIN_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('mod_nhansu');
    }
    function index() {
        $this->curl->create($this->_api['account']['getinfo']);
        $this->curl->post(array(
            'uid' => $this->session->userdata('ssAccountId'),
            'project' => _PROJECT_ID
        ));
        $profile = json_decode($this->curl->execute(), TRUE);
        // Get nhansu info
        $nhansu_info = $this->mod_nhansu->get_nhansu_info($this->session->userdata('ssAdminId'));
        if($nhansu_info && $nhansu_info['nhansu_sign']){
            $nhansu_info['nhansu_sign'] = site_url()._UPLOADS_URL.'nhansu/sign/'.$nhansu_info['nhansu_sign'];
        }
        $this->parser->assign('profile', $profile);
        $this->parser->assign('nhansu_info', $nhansu_info);
        $this->parser->parse('profile/detail');
    }
    function logout() {
        $cached = 'session_id'.$this->session->userdata('ssAdminId');
        if (file_exists("cache/cached/{$this->_host[0]}/{$cached}.json")) unlink("cache/cached/{$this->_host[0]}/{$cached}.json");
		$this->session->sess_destroy();
        redirect(site_url());
    }
    function changePwd() {
        if ($this->input->post('isSent') == 'OK') {
            $items = $this->input->post();
            if ($items['password_new'] != $items['password_new_confirm']) redirect(site_url().'admin/warning?w=confirm');
            else {
                $this->curl->create($this->_api['account']['changepwd']);
                $this->curl->post(array(
                    'aid' => $this->session->userdata('ssAccountId'),
                    'password_old' => $items['password_old'],
                    'password_new' => $items['password_new_confirm'],
                    'project' => _PROJECT_ID
                ));
                $change = json_decode($this->curl->execute());
                if (isset($change->err_code) && $change->err_code == 100) {
                    $data = array(
                        'nhansu_id' => $this->session->userdata('ssAdminId'),
                        'nhansu_password' => NULL
                    );
                    $this->mod_nhansu->doimatkhau($data);
                    redirect(site_url().'admin/warning');
                }
                else redirect(site_url().'admin/warning?w=wrong');
            }
        }
    }
    function listmods() {
        if (!$this->session->userdata('ssAccountId')) redirect(site_url().'admin/login');
        $listmods = array();
        foreach ($this->permarr as $key=>$mod) {
            $listmods[$mod['group']][$key] = $mod;
        }
        $this->parser->assign('items', $listmods);
        $this->parser->parse('profile/listmods');
    }
    function ajax_upload_sign(){
        $output = array('code' => 0);
        if($_FILES && isset($_FILES['file'])) {
            if ($_FILES['avatar']['error'] <= 0) {
                // Upload file
                $sign_name = 'sign_'.$this->session->userdata('ssAdminId').'.'.pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                move_uploaded_file($_FILES['file']['tmp_name'], './'._UPLOADS_PATH.'nhansu/sign/'.$sign_name);
                // Update nhansu sign
                $data = array(
                    'nhansu_id' => $this->session->userdata('ssAdminId'),
                    'nhansu_sign' => $sign_name
                );
                $this->mod_nhansu->update_sign($data);
                $output['code'] = 1;
                $output['nhansu_sign'] = site_url()._UPLOADS_URL.'nhansu/sign/'.$sign_name;
            }   
        }
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
