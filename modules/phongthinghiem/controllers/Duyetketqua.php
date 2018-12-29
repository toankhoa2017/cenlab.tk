<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Duyetketqua extends ADMIN_Controller {
    private $api_nhansu_url = 'http://dev.cenlab.vn/nhansu/api/';
    private $ptn_id;
    private $user_login = false;
    private $ketqua_txt = array(
        '0' => array('label' => 'Đang chờ duyệt', 'icon' => '<i class="fa fa-clock-o orange" aria-hidden="true"></i>', 'class' => 'tooltip-warning'),
        '1' => array('label' => 'Đồng ý', 'icon' => '<i class="fa fa-check-circle green" aria-hidden="true"></i>', 'class' => 'tooltip-success'),
        '2' => array('label' => 'Không đồng ý', 'icon' => '<i class="fa fa-times-circle red" aria-hidden="true"></i>', 'class' => 'tooltip-error'),
        //'3' => array('label' => 'Phân tích lại', 'icon' => '<i class="fa fa-pencil-square orange" aria-hidden="true"></i>', 'class' => 'tooltip-warning')
    );
    function __construct() {
        parent::__construct();
        $this->load->library('curl');
        $this->load->model('mod_hopdong', 'hopdong');
        $this->load->model('mod_phongthinghiem', 'phongthinghiem');
        $this->load->model('mod_suco', 'suco');
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
            $this->api_nhansu_url = $this->_api['nhansu'];
        }
    }
    function index(){
        $this->parser->parse('duyetketqua/index');
    }
    function detail(){
        $mauchitiet_id = $this->input->get('mauchitiet');
        $package = $this->phongthinghiem->get_list_chat_package($mauchitiet_id, $this->ptn_id);
        if($package){
            // Call API get donvi info
            $this->curl->create($this->api_nhansu_url.'get_donvi');
            $this->curl->post(array(
                'donvi_id' => trim($package['donvi_id'])
            ));
            $result = json_decode($this->curl->execute(), TRUE);
            if($result['err_code'] == 200){
                $package['ptn_name'] = $result['donvi_name'];
            }
            // Get suco info
            $chitieu_suco = array();
            $list_suco = $this->suco->get_suco_mauchitiet($mauchitiet_id);
            if($list_suco){
                foreach ($list_suco as &$suco_info){
                    if($suco_info['suco_approve'] != '2'){
                        // Add suco mauchitiet listchat
                        if(is_array($chitieu_suco[$suco_info['mauchitiet_id']])){
                            $chitieu_suco[$suco_info['mauchitiet_id']] = array_merge($chitieu_suco[$suco_info['mauchitiet_id']], json_decode($suco_info['list_chat'], TRUE));
                        }else{
                            $chitieu_suco[$suco_info['mauchitiet_id']] = json_decode($suco_info['list_chat'], TRUE);
                        }
                    }
                }
            }
            // Get lits chat
            $list_chat = $this->hopdong->get_list_chat_id(json_decode($package['list_chat'], TRUE), $package['dongia_id']);
            if($list_chat){
                foreach ($list_chat as &$chat){
                    if(in_array($chat['chat_id'], $chitieu_suco[$mauchitiet_id])){
                        $chat['chat_suco'] = TRUE;
                    }else{
                        $chat['chat_suco'] = FALSE;
                    }
                }
            }
            $package['list_chat'] = $list_chat;
            // Get ketqua
            $mauketqua_list = $this->phongthinghiem->get_mauketqua_list($mauchitiet_id);
            if($mauketqua_list){
                foreach ($mauketqua_list as &$mauketqua){
                    $mauketqua['mauketqua_approve_txt'] = $this->ketqua_txt[$mauketqua['mauketqua_approve']];
                    $mauketqua['list_ketqua'] = json_decode($mauketqua['list_ketqua'], TRUE);
                }
            }
            $package['mauketqua_list'] = $mauketqua_list;
        }
        $last_mauketqua = end($package['mauketqua_list']);
        // Save approve
        $data = $this->input->post();
        if($data){
            $last_mauketqua = end($package['mauketqua_list']);
            if($last_mauketqua['mauketqua_approve'] == '0'){
                $approve = $this->phongthinghiem->update_mauketqua_duyet(array(
                    'user_approve' => $this->user_login,
                    'date_approve' => date("Y-m-d H:i:s"),
                    'mauketqua_approve' => trim($data['mauketqua_approve']),
                    'mauketqua_duyet_id' => trim($last_mauketqua['mauketqua_duyet_id'])
                ));
                if($approve){
                    redirect(site_url().'phongthinghiem/duyetketqua/detail?mauchitiet='.$mauchitiet_id.'&result=true');
                }
            }
        }
        $data = array(
            'package' => $package,
            'result' => $this->input->get('result')
        );
        $this->parser->assign('data', $data);
        $this->parser->parse('duyetketqua/detail');
    }
    function ajax_list_package(){
        $data = $this->input->get();
        // Get param draw
        $draw = $data['draw'] != null ? $data['draw'] : 1;
        // Sort record
        $sort_column = $data['order'][0]['column'] != null ? $data['order'][0]['column'] : -1;
        $sort_direction = $data['order'][0]['dir'] != null ? $data['order'][0]['dir'] : 'DESC';
        // Limit record
        $start = $data['start'] != null ? $data['start'] : 0;
        $length = $data['length'] != null ? $data['length'] : 10;
        // Get param search
        $search = $data['search']['value'] != null ? $data['search']['value'] : '';
        // Count list all hopdong
        $count_all_hopdong = $this->phongthinghiem->count_all_package_duyet($this->ptn_id);
        // Count list search hopdong
        $count_list_hopdong = $this->phongthinghiem->count_list_package_duyet($search, $this->ptn_id);
        // Get list certificate
        $list_package = $this->phongthinghiem->get_chitieu_mau_duyet($search, $sort_column, $sort_direction, $start, $length, $this->ptn_id);
        // Build data        
        $data_package = array();
        foreach ($list_package as &$package){
            $package['action'] = '';
            // Get chitieu suco
            // Get suco info
            $chitieu_suco = array();
            $list_suco = $this->suco->get_suco_mauchitiet($package['mauchitiet_id']);
            if($list_suco){
                foreach ($list_suco as &$suco_info){
                    if($suco_info['suco_approve'] != '2'){
                        // Add suco mauchitiet listchat
                        if(is_array($chitieu_suco[$suco_info['mauchitiet_id']])){
                            $chitieu_suco[$suco_info['mauchitiet_id']] = array_merge($chitieu_suco[$suco_info['mauchitiet_id']], json_decode($suco_info['list_chat'], TRUE));
                        }else{
                            $chitieu_suco[$suco_info['mauchitiet_id']] = json_decode($suco_info['list_chat'], TRUE);
                        }
                    }
                }
            }
            // Create data tmp
            $data_tmp = array();
            $data_tmp['index'] = ++$start;
            foreach ($package as $key=>$value){
                if($key == 'list_chat'){
                    $list_chat = $this->hopdong->get_list_chat_id(json_decode($value, TRUE), $package['dongia_id']);
                    if($list_chat){
                        foreach ($list_chat as &$chat){
                            if(in_array($chat['chat_id'], $chitieu_suco[$package['mauchitiet_id']])){
                                $chat['chat_suco'] = TRUE;
                            }else{
                                $chat['chat_suco'] = FALSE;
                            }
                        }
                    }
                    $value = $list_chat;
                }
                if($key == 'donvi_id'){
                    // Call API
                    $this->curl->create($this->api_nhansu_url.'get_donvi');
                    $this->curl->post(array(
                        'donvi_id' => trim($value)
                    ));
                    $result_api = json_decode($this->curl->execute(), TRUE);
                    if($result_api['err_code'] == 200){
                        $package['ptn_name'] = $result_api['donvi_name'];
                    }
                }
                if($key == 'user_approve' && $value){
                    // Call API
                    $this->curl->create($this->api_nhansu_url.'nhansu_info');
                    $this->curl->post(array(
                        'nhansu_id' => $value
                    ));
                    $result_api = json_decode($this->curl->execute(), TRUE);
                    if($result_api['err_code'] == 200){
                        $value = $result_api['nhansu'][0]['nhansu_lastname'].' '.$result_api['nhansu'][0]['nhansu_firstname'];
                    }
                }
                if($key == 'mauketqua_approve'){
                    $package['mauketqua_approve_txt'] = $this->ketqua_txt[$value];
                }
                $data_tmp[$key] = $value;
            }
            $data_package[] = $data_tmp;
        }
        $result['draw'] = $draw;
        $result['recordsTotal'] = $count_all_hopdong;
        $result['recordsFiltered'] = $count_list_hopdong;
        $result['data'] = $data_package;
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit;
    }
}