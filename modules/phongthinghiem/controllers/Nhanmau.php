<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Nhanmau extends ADMIN_Controller {
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
        $this->load->model('mod_phongthinghiem', 'phongthinghiem');
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
        $this->parser->parse('nhanmau/index');
    }
    function detail(){
        $mau_id = $this->input->get('mau');
        $mau_info = $list_chitieu = $list_suco = false;
        if($mau_id){
            // Get mau info
            $mau_info = $this->phongthinghiem->get_mau_info($mau_id, $this->ptn_id);
            if($mau_info && $mau_info['nhansu_id']){
                // Call API user create
                $this->curl->create($this->api_nhansu_url.'nhansu_info');
                $this->curl->post(array(
                    'nhansu_id' => trim($mau_info['nhansu_id'])
                ));
                $result = json_decode($this->curl->execute(), TRUE);
                if($result['err_code'] == 200){
                    $mau_info['nhansu'] = $result['nhansu'][0];
                }
            }
            if($mau_info){
                // Call api to luumau
                $this->curl->create($this->api_luumau_url.'getStatusMau');
                $this->curl->post(array(
                    'mau_id' => trim($mau_id)
                ));
                $result = json_decode($this->curl->execute(), TRUE);
				
                if($result['err_code'] == 200){
                    if($result['user_id']){
                        // Call API user create
                        $this->curl->create($this->api_nhansu_url.'nhansu_info');
                        $this->curl->post(array(
                            'nhansu_id' => trim($result['user_id'])
                        ));
                        $result = json_decode($this->curl->execute(), TRUE);
                        if($result['err_code'] == 200){
                            $mau_info['mau_vitri'] = $result['nhansu'][0]['donvi_ten'];
                        }
                    }elseif($result['kho_name']){
                        $mau_info['mau_vitri'] = $result['kho_name'];
                    }
                }
            }
            // Get suco info
            $chitieu_suco = array();
            if($mau_info){
                $list_suco = $this->suco->get_suco_mau($mau_info['mau_id']);
                if($list_suco){
                    foreach ($list_suco as &$suco_info){
                        // Get suco chitiet
                        $list_suco_chitiet = $this->suco->get_suco_chitieu($suco_info['suco_id']);
                        if($list_suco_chitiet){
                            foreach ($list_suco_chitiet as &$suco_chitiet){
                                // Get chat info
                                $list_chat = $this->phongthinghiem->get_list_chat_info(json_decode($suco_chitiet['list_chat'], TRUE));
                                if($list_chat){
                                    $suco_chitiet['list_chat_info'] = $list_chat;
                                }
                                // Add suco mauchitiet listchat
                                if(is_array($chitieu_suco[$suco_chitiet['mauchitiet_id']])){
                                    $chitieu_suco[$suco_chitiet['mauchitiet_id']] = array_merge($chitieu_suco[$suco_chitiet['mauchitiet_id']], json_decode($suco_chitiet['list_chat'], TRUE));
                                }else{
                                    $chitieu_suco[$suco_chitiet['mauchitiet_id']] = json_decode($suco_chitiet['list_chat'], TRUE);
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
                        // Status suco
                        $suco_info['suco_approve_info'] = $this->suco_approve_txt[$suco_info['suco_approve']];
                    }
                }
            }
            // Get list chitieu
            $list_chitieu = $this->phongthinghiem->get_list_chitieu_mau($mau_id, $this->ptn_id);
            if($list_chitieu){
                foreach ($list_chitieu as &$chitieu){
                    $list_chat = $this->phongthinghiem->get_list_chat_info(json_decode($chitieu['list_chat'], TRUE));
                    if($list_chat){
                        foreach ($list_chat as &$chat){
                            if(in_array($chat['chat_id'], $chitieu_suco[$chitieu['mauchitiet_id']])){
                                $chat['chat_suco'] = TRUE;
                            }else{
                                $chat['chat_suco'] = FALSE;
                            }
                        }
                        $chitieu['list_chat_info'] = $list_chat;
                    }
                }
            }
        }
        $data = array(
            'mau_info' => $mau_info,
            'list_suco' => $list_suco,
            'list_chitieu' => $list_chitieu,
            'chitieu_suco' => $chitieu_suco
        );
        $this->parser->assign('data', $data);
        $this->parser->parse('nhanmau/detail');
    }
    function ajax_list_mau(){
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
        $count_all_hopdong = $this->phongthinghiem->count_all_mau($this->ptn_id);
        // Count list search hopdong
        $count_list_hopdong = $this->phongthinghiem->count_list_mau($search, $this->ptn_id);
        // Get list certificate
        $list_package = $this->phongthinghiem->get_list_mau($search, $sort_column, $sort_direction, $start, $length, $this->ptn_id);
        // Build data        
        $data_package = array();
        foreach ($list_package as &$package){
            $package['action'] = '';
            //$data_hopdong[] = array_values($hopdong);
            $data_tmp = array();
            $data_tmp['index'] = ++$start;
            foreach ($package as $key=>$value){
                if($key == 'list_chat'){
                    $list_chat = $this->hopdong->get_list_chat_id(json_decode($value, TRUE), $package['dongia_id']);
                    //$value = implode('|',array_values(array_column($list_chat, 'chat_name')));
                    $value = $list_chat;
                }
                if($key == 'list_ketqua'){
                    
                }
                if($key == 'donvi_id'){
                    // Call API
                    $this->curl->create($this->api_nhansu_url.'get_donvi');
                    $this->curl->post(array(
                        'donvi_id' => trim($value)
                    ));
                    $result = json_decode($this->curl->execute(), TRUE);
                    if($result['err_code'] == 200){
                        $package['ptn_name'] = $result['donvi_name'];
                    }
                }
                if($key == 'mauketqua_approve'){
                    $value = array('mauketqua_approve' => $value, 'mauketqua_approve_txt' => $this->ketqua_txt[$value]);
                    //var_dump($package);exit;
                }
                if($key == 'date_create' && $value){
                    $value = date("d/m/Y H:i:s", strtotime($value));
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
    function ajax_nhanmau(){
        $output = array();
        $mau_id = $this->input->post('mau_id');
        $mauptn_approve = $this->input->post('mauptn_approve');
        $mauptn_note= $this->input->post('mauptn_note');
        if($mau_id){
            $mau_info = $this->phongthinghiem->get_mau_id($mau_id);
            // Call api to luumau
            $luumau = true;
            if($mau_info && $mauptn_approve == '1'){
                $this->curl->create($this->api_luumau_url.'luuMau');
                $this->curl->post(array(
                    'mau_id' => trim($mau_id),
                    'luumau_name' => $mau_info['mau_name'],
                    'nhansu_id' => $this->user_login,
                    'luumau' => array(
                        $this->input->post('mau_0'), $this->input->post('mau_1')
                    )
                ));
                $result = json_decode($this->curl->execute(), TRUE);
                if($result['err_code'] != 200){
                    $luumau = false;
                }
            }
            if($luumau){
                $insert_mauptn = $this->phongthinghiem->insert_mauptn(array(
                    'mau_id' => $mau_id,
                    'donvi_id' => $this->ptn_id,
                    'nhansu_id' => $this->user_login,
                    'mauptn_approve' => $mauptn_approve,
                    'mauptn_note' => $mauptn_note,
                    'mauptn_createdate' => date("Y-m-d H:i:s")
                ));
                if($insert_mauptn){
                    $output['code'] = 1;
                    $output['message'] = 'Cập nhật mẫu thành công';
                }else{
                    $output['code'] = 0;
                    $output['message'] = 'Không cập nhật được dữ liệu';
                }
            }else{
                $output['code'] = 0;
                $output['message'] = 'Không cập nhật được dữ liệu lưu mẫu';
            }
        }else{
            $output['code'] = 0;
            $output['message'] = 'Mẫu không tồn tại';
        }
        echo json_encode($output);
        
    }
    function ajax_suso(){
        $output = array();
        $hopdong_id = $this->input->post('hopdong_id');
        $chitieu_suco = $this->input->post('chitieu_suco');
        $suco_note = $this->input->post('suco_note');
        if($hopdong_id && $chitieu_suco && is_array($chitieu_suco)){
            $insert_suco = $this->suco->insert_suco(array(
                'suco_type' => 2,
                'suco_content' => $suco_note,
                'hopdong_id' => $hopdong_id,
                'nhansu_id' => $this->user_login,
                'suco_createdate' => date("Y-m-d H:i:s")
            ));
            if($insert_suco){
                $suco_chitiet = array();
                foreach ($chitieu_suco as $mauchitiet_id => $list_chat){
                    $suco_chitiet[] = array(
                        'suco_id' => $insert_suco,
                        'mauchitiet_id' => $mauchitiet_id,
                        'list_chat' => json_encode($list_chat)
                    );
                }
                $insert_suco_chitiet = $this->suco->insert_suco_chitiet($suco_chitiet);
                if($insert_suco_chitiet){
                    $output['code'] = 1;
                    $output['message'] = 'Thêm phiếu báo sự cố thành công';
                }else{
                    $output['code'] = 0;
                    $output['message'] = 'Không cập nhật được dữ liệu phiếu báo sự cố chi tiết';
                }
            }else{
                $output['code'] = 0;
                $output['message'] = 'Không cập nhật được dữ liệu phiếu báo sự cố';
            }
        }else{
            $output['code'] = 0;
            $output['message'] = 'Kiểm tra lại thông tin trước khi tiếp tục';
        }
        echo json_encode($output);
    }
}