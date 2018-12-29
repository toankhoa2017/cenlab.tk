<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Suco extends ADMIN_Controller {
    private $api_nhansu_url = 'http://dev.cenlab.vn/nhansu/api/';
    private $api_luumau_url = 'http://dev.cenlab.vn/luumau/api/';
    private $ptn_id;
    private $user_login = false;
    private $suco_approve_txt = array(
        '0' => array('label' => 'Đang chờ duyệt', 'icon' => '<i class="fa fa-clock-o orange" aria-hidden="true"></i>', 'class' => 'orange'),
        '1' => array('label' => 'Đồng ý', 'icon' => '<i class="fa fa-check-circle green" aria-hidden="true"></i>', 'class' => 'green'),
        '2' => array('label' => 'Không đồng ý', 'icon' => '<i class="fa fa-times-circle red" aria-hidden="true"></i>', 'class' => 'red')
    );
    function __construct() {
        parent::__construct();
        $this->load->library('curl');
        $this->load->model('mod_hopdong', 'hopdong');
        $this->load->model('mod_nenmau', 'nenmau');
        $this->load->model('mod_suco', 'suco');
        // Set ptn id
        if(ENVIRONMENT === 'local'){
            $this->ptn_id = 2;
            $this->user_login = 7;
        }else{
            $this->ptn_id = $this->session->userdata()['ssAdminDonvi'];
            if(!$this->ptn_id){
                    redirect(site_url().'admin/login');
            }
            $this->user_login = $this->session->userdata()['ssAdminId'];
            if(!$this->user_login){
                    redirect(site_url().'admin/login');
            }
            $this->api_nhansu_url = $this->_api['nhansu'];
            $this->api_luumau_url = $this->_api['luumau'];
        }
    }
    function index(){
        $hopdong_id = $this->input->get('hopdong_id');
        // Get hopdong info
        $hopdong = $this->hopdong->get_hopdong_id($hopdong_id);
        if($hopdong){
            // Call API user create
            $this->curl->create($this->api_nhansu_url.'nhansu_info');
            $this->curl->post(array(
                'nhansu_id' => trim($hopdong['nhansu_id'])
            ));
            $result = json_decode($this->curl->execute(), TRUE);
            if($result['err_code'] == 200){
                $hopdong['nhansu'] = $result['nhansu'][0];
            }
        }
        // Get list suco chitieu
        $list_suco = $this->suco->get_suco_hopdong_mau($hopdong_id);
        if($list_suco){
            foreach ($list_suco as &$suco_info){
                // Get suco chitiet
                $list_suco_chitiet = $this->suco->get_suco_chitieu($suco_info['suco_id']);
                if($list_suco_chitiet){
                    foreach ($list_suco_chitiet as &$suco_chitiet){
                        // Get chat info
                        $list_chat = $this->nenmau->get_list_chat_info(json_decode($suco_chitiet['list_chat'], TRUE));
                        if($list_chat){
                            $suco_chitiet['list_chat_info'] = $list_chat;
                        }
                    }
                }
                $suco_info['list_suco_chitiet'] = $list_suco_chitiet;
                // Call API user create
                $this->curl->create($this->api_nhansu_url.'nhansu_info');
                $this->curl->post(array(
                    'nhansu_id' => trim($suco_info['nhansu_id'])
                ));
                $result = json_decode($this->curl->execute(), TRUE);
                if($result['err_code'] == 200){
                    $suco_info['nhansu'] = $result['nhansu'][0];
                }
            }
        }
        // Get list suco khachhang
        $list_suco_khachhang = $this->suco->get_suco_hopdong_khachhang($hopdong_id);
        if($list_suco_khachhang){
            foreach ($list_suco_khachhang as &$suco_khachhang_info){
                // Call API user create
                $this->curl->create($this->api_nhansu_url.'nhansu_info');
                $this->curl->post(array(
                    'nhansu_id' => trim($suco_khachhang_info['nhansu_id'])
                ));
                $result = json_decode($this->curl->execute(), TRUE);
                if($result['err_code'] == 200){
                    $suco_khachhang_info['nhansu'] = $result['nhansu'][0];
                }
            }
        }
        $data = array(
            'hopdong' => $hopdong,
            'list_suco' => $list_suco,
            'list_suco_khachhang' => $list_suco_khachhang
        );
        $this->parser->assign('data', $data);
        $this->parser->parse('suco/index');
    }
}