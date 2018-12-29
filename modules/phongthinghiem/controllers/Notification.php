<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Notification extends ADMIN_Controller{
    private $ptn_id;
    private $user_login = false;
    function __construct() {
        parent::__construct();
        $this->load->model('mod_phongthinghiem', 'phongthinghiem');
        // Set ptn id
        if(ENVIRONMENT === 'local'){
            $this->ptn_id = 2;
            $this->user_login = 1;
        }else{
            $this->ptn_id = $this->session->userdata()['ssAdminDonvi'];
            if(!$this->ptn_id){
                redirect(site_url().'admin/login');
            }
            $this->user_login = $this->session->userdata()['ssAdminId'];
            if(!$this->user_login){
                redirect(site_url().'admin/login');
            }
        }
    }
    function index(){
        $count_all_package = $this->phongthinghiem->count_all_package($this->ptn_id);
        $count_package_ketqua = $this->phongthinghiem->count_package_ketqua($this->ptn_id);
        $count_all_package_duyet = $this->phongthinghiem->count_all_package_duyet($this->ptn_id);
        $count_package_duyet = $this->phongthinghiem->count_package_duyet($this->ptn_id);
        $data = array(
            'count_all_package' => $count_all_package,
            'count_package_ketqua' => $count_package_ketqua,
            'count_all_package_duyet' => $count_all_package_duyet,
            'count_package_duyet' => $count_package_duyet
        );
        $this->parser->assign('data', $data);
        $this->parser->parse('notification/index');
    }
}