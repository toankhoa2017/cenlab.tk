<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hopdong extends ADMIN_Controller {
    private $api_khachhang_url = 'http://dev.cenlab.vn/khachhang/api/';
    private $api_nhansu_url = 'http://dev.cenlab.vn/nhansu/api/';
    private $hopdong_approve = array(
        '0' => 'Chưa duyệt',
        '1' => 'Đồng ý',
        '2' => 'Hủy hợp đồng',
        '3' => 'Sửa hợp đồng',
        '4' => 'PTN duyệt'
    );
    private $user_login = false;
    function __construct() {
        parent::__construct();
        $this->load->library('curl');
        $this->load->model('mod_nenmau', 'nenmau');
        $this->load->model('mod_hopdong', 'hopdong');
        // Set user login
        if(ENVIRONMENT === 'local'){
            $this->user_login = 1;
         
            define('_TKQ_PHIEUKQ', 68);
            
            $this->permarr = array(
                68 => array('write' => 'checked', 'update' => 'checked'),
            );
        }else{
            $this->user_login = $this->session->userdata()['ssAdminId'];
            if(!$this->user_login){
                    redirect(site_url().'admin/login');
            }
            $this->api_khachhang_url = $this->_api['khachhang'];
            $this->api_nhansu_url = $this->_api['nhansu'];
        }
    }
    function detail(){
        $data = array();
        $hopdong_code = $this->input->get('hopdong');
        // Check permission
        $permission = $this->permarr;
        if(!$permission || !isset($permission[_TKQ_PHIEUKQ]) || (!$permission[_TKQ_PHIEUKQ]['write'] && !$permission[_TKQ_PHIEUKQ]['master'])){
            redirect(site_url().'admin/denied?w=write');
            exit;
        }
        $data['hopdong'] = $this->get_hopdong($hopdong_code);
        $this->parser->assign('data', $data);
        $this->parser->parse('hopdong/detail');
    }
    private function get_hopdong($hopdong_code){
        $hopdong = $this->hopdong->get_hopdong_code($hopdong_code);
        if($hopdong){
            // Get hopdong approve
            $hopdong['hopdong_approve_txt'] = $this->hopdong_approve[$hopdong['hopdong_approve']];
            
            // Call API congty
            $this->curl->create($this->api_khachhang_url.'list_congty');
            $this->curl->post(array(
                'congty_id' => trim($hopdong['congty_id'])
            ));
            $result = json_decode($this->curl->execute(), TRUE);
            if($result['err_code'] == 200){
                $hopdong['congty'] = $result['list_congty'][0];
            }
            
            // Call API contact
            $this->curl->create($this->api_khachhang_url.'list_contact');
            $this->curl->post(array(
                'contact_id' => trim($hopdong['contact_id'])
            ));
            $result = json_decode($this->curl->execute(), TRUE);
            if($result['err_code'] == 200){
                $hopdong['contact'] = $result['list_contact'][0];
            }
            
            // Call API user create
            $this->curl->create($this->api_nhansu_url.'nhansu_info');
            $this->curl->post(array(
                'nhansu_id' => trim($hopdong['nhansu_id'])
            ));
            $result = json_decode($this->curl->execute(), TRUE);
            if($result['err_code'] == 200){
                $hopdong['nhansu_info'] = $result['nhansu'][0];
            }
            // Get mau of hopdong
            $list_mau = $this->hopdong->get_mau_hopdong($hopdong['hopdong_id']);
            if($list_mau){
                $list_thitruong = [];
                foreach ($list_mau as &$mau){
                    // Get list chitieu
                    $list_chitieu = $this->hopdong->get_chitieu_mau($mau['mau_id']);                 
                    if($list_chitieu){
                        foreach ($list_chitieu as &$chitieu){
                            // Call API get PTN info
                            $this->curl->create($this->api_nhansu_url.'get_donvi');
                            $this->curl->post(array(
                                'donvi_id' => trim($chitieu['donvi_id'])
                            ));
                            $result = json_decode($this->curl->execute(), TRUE);
                            if($result['err_code'] == 200){
                                $chitieu['ptn_name'] = $result['donvi_name'];
                            }
                            // List chat
                            $chitieu['list_chat_id'] = implode(',', json_decode($chitieu['list_chat'], TRUE));
                            $list_chat = $this->hopdong->get_list_chat_id(json_decode($chitieu['list_chat'], TRUE), $chitieu['dongia_id']);
                            if($list_chat){
                                foreach ($list_chat as &$chat){
                                    // Get class for item in list chat
                                    $chat['class'] = 'chat-'.$chat['chat_id'];
                                    /*
                                    if($chitieu['lod_loq']=='1'){
                                        $chat['class'] = 'chat-1-'.$chat['chat_id'];
                                    }else{
                                        $chat['class'] = 'chat-2-'.$chat['chat_id'];
                                    }*/
                                    // LOD, LOQ from thitruong_chat
                                    $thitruong = $this->nenmau->getThiTruongChat($chat['chat_id']);
                                    if($thitruong){
                                        // LOD, LOQ from chitieu_chat
                                        $thitruong[] = array(
                                            'capacity' => $chat['capacity'],
                                            'val_min' => $chat['val_min'],
                                            'val_max' => $chat['val_max'],
                                            'thitruong_id' => 0
                                        );
                                        $list_thitruong[$chat['chat_id']] = $thitruong;
                                    }
                                }
                                
                            }
                            $chitieu['list_chat'] = $list_chat;
                            // LOD_LOQ
                            $chitieu['lod_loq_txt'] = $chitieu['lod_loq']=='1'?'LOD':($chitieu['lod_loq']=='2'?'LOQ':'LOD & LOQ');
                            // Get mauketqua_duyet
                            if($chitieu['mauketqua_duyet_id']){
                                $mau_ketqua_duyet = $this->hopdong->get_mau_ketqua_duyet($chitieu['mauketqua_duyet_id']);
                                if($mau_ketqua_duyet){
                                    $chitieu['mauketqua_approve'] = $mau_ketqua_duyet['mauketqua_approve'];
                                }
                            }
                            // Rewrite phuongphap name
                            if($chitieu['phuongphap_loai'] === '2'){
                                $chitieu['phuongphap_name'] = $chitieu['phuongphap_shortname'];
                            }else{
                                $chitieu['phuongphap_name'] = $chitieu['phuongphap_shortname']?$chitieu['phuongphap_code'].' '.$chitieu['phuongphap_shortname']:$chitieu['phuongphap_code'];
                            }
                        }
                    }
                    $mau['list_chitieu'] = $list_chitieu?$list_chitieu:false;
                }
                $hopdong['list_mau'] = $list_mau;
                $hopdong['list_thitruong'] = $list_thitruong;
            }
        }
        return $hopdong;
    }
    function ajax_call_api(){
        $url_api = $this->api_khachhang_url;
        $data = array();
        $value = $this->input->post('value');
        if($this->input->post('congty_contact') == 'congty'){
            $id_congty = $this->input->post('id_current');
            switch($this->input->post('type')){
                case 'phone':
                    $url_api = $url_api . 'check_phone_congty';
                    $data = array('congty_phone' => $value, 'id_congty' => $id_congty );
                    break;
                case 'email':
                    $url_api = $url_api . 'check_email_congty';
                    $data = array('congty_email' => $value, 'id_congty' => $id_congty );
                    break;
                case 'tax':
                    $url_api = $url_api . 'check_tax_congty';
                    $data = array('congty_tax' => $value, 'id_congty' => $id_congty );
                    break;
            }
        }else{
            $contact_id = $this->input->post('id_current');
            switch($this->input->post('type')){
                case 'phone':
                    $url_api = $url_api . 'check_phone_contact';
                    $data = array('contact_phone' => $value, 'contact_id' => $contact_id );
                    break;
                case 'email':
                    $url_api = $url_api . 'check_email_contact';
                    $data = array('contact_email' => $value, 'contact_id' => $contact_id );
                    break;
            }
        }
        $this->curl->create($url_api);
        $this->curl->post($data);
        $result = json_decode($this->curl->execute(), TRUE);
        echo json_encode($result);
    }
}