<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Notification extends ADMIN_Controller{
    private $ptn_id;
    private $user_login = false;
    function __construct() {
        parent::__construct();
        $this->load->model('mod_phongthinghiem', 'phongthinghiem');
        $this->load->model('mod_ketqua', 'ketqua');
        // Set ptn id
        if(ENVIRONMENT === 'local'){
            $this->ptn_id = 2;
            $this->user_login = 69;
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
        $count_package_duyet_accept = $this->phongthinghiem->count_package_duyet_accept();
        $count_package_export = $this->phongthinghiem->count_package_export();
        $count_all_approve = $this->ketqua->count_all_approve($this->user_login, true);
        $count_all_not_approve = $this->ketqua->count_all_approve($this->user_login, false);
        $data = array(
            'count_package_duyet_accept' => $count_package_duyet_accept,
            'count_package_export' => $count_package_export,
            'count_all_approve' => $count_all_approve,
            'count_all_ketqua' => $count_all_approve + $count_all_not_approve
        );
        $this->parser->assign('data', $data);
        $this->parser->parse('notification/index');
    }
}