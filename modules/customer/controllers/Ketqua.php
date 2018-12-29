<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ketqua extends KH_Controller {
    private $api_khachhang_url = 'http://dev.tamducjsc.info/khachhang/api/';
    private $api_nhansu_url = 'http://dev.tamducjsc.info/nhansu/api/';
    private $api_general_url = 'http://dev.tamducjsc.info/general/api/';
    private $hopdong_approve = array(
        '0' => 'Chưa duyệt',
        '1' => 'Đồng ý',
        '2' => 'Hủy hợp đồng',
        '3' => 'Sửa hợp đồng'
    );
    private $phanhoi_approve_txt = array(
        '0' => array('label' => 'Đang chờ duyệt', 'icon' => '<i class="fa fa-clock-o orange" aria-hidden="true"></i>', 'class' => 'orange'),
        '1' => array('label' => 'Đồng ý', 'icon' => '<i class="fa fa-check-circle green" aria-hidden="true"></i>', 'class' => 'green'),
        '2' => array('label' => 'Không đồng ý', 'icon' => '<i class="fa fa-times-circle red" aria-hidden="true"></i>', 'class' => 'red')
    );
    private $ptn_id;
    private $user_login = false;
    private $congty_id = array();
    private $upload_path;
    private $upload_url;
    function __construct() {
        parent::__construct();
        $this->load->library('curl');
        $this->load->model('mod_hopdong', 'hopdong');
        $this->load->model('mod_ketqua', 'ketqua');
        $this->load->model('mod_phanhoi', 'phanhoi');
        // Set ptn id
        if(ENVIRONMENT === 'local'){
            $this->ptn_id = 2;
            $this->upload_path = '_uploads/';
            $this->upload_url = base_url().'_uploads/';
        }else{
			/*
            $this->ptn_id = $this->session->userdata()['ssAdminDonvi'];
            if(!$this->ptn_id){
                redirect(site_url().'admin/login');
            }*/
            $this->upload_path = _UPLOADS_PATH;
            $this->upload_url = base_url()._UPLOADS_URL;
        }
        // Set user login
        if(ENVIRONMENT === 'local'){
            $this->user_login = 7;
            $this->congty_id = array('9');
        }else{
            $this->user_login = $this->session->userdata()['ssCustomerId'];
            if(!$this->user_login){
                redirect(site_url().'customer/login');
            }
            $this->congty_id = $this->session->userdata()['ssCongtyArr']?json_decode($this->session->userdata()['ssCongtyArr'], TRUE):false;
            $this->api_khachhang_url = $this->_api['khachhang'];
            $this->api_nhansu_url = $this->_api['nhansu'];
            $this->api_general_url = $this->_api['general'];
        }
    }
    function danhsachketqua(){
        $data = array(
			'congty' => $this->congty_id
		);
        $this->parser->assign('data', $data);
        $this->parser->parse('ketqua/danhsachketqua');
    }
    function ajax_list(){
        $data = $this->input->get();
        // $this->congty_id = array($data['congty']);
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
        // Count list all ketqua
        $count_all = $this->ketqua->count_all($this->congty_id);
        // Count list search ketqua
        $count_list = $this->ketqua->count_list($this->congty_id, $search);
        // Get list certificate
        $list_ketqua = $this->ketqua->get_list($this->congty_id, $search, $sort_column, $sort_direction, $start, $length);
        // Build data        
        $data_ketqua = array();
        if($list_ketqua){
            foreach ($list_ketqua as $ketqua){
                $data_tmp = $ketqua;
                $data_tmp['index'] = ++$start;
                // Add link to record
                $data_tmp['link_detail'] = site_url().'customer/ketqua/detail?ketqua='.$ketqua['ketqua_id'];
                // Call API congty
                $data_tmp['congty_name'] = '';
                $this->curl->create($this->api_khachhang_url.'list_congty');
                $this->curl->post(array(
                    'congty_id' => trim($ketqua['congty_id'])
                ));
                $result = json_decode($this->curl->execute(), TRUE);
                if($result['err_code'] == 200){
                    $data_tmp['congty_name'] = $result['list_congty'][0]['congty_name'];
                }
                // Call API get user create ketqua
                $this->curl->create($this->api_nhansu_url.'nhansu_info');
                $this->curl->post(array(
                    'nhansu_id' => trim($ketqua['user_id'])
                ));
                $result = json_decode($this->curl->execute(), TRUE);
                if($result['err_code'] == 200){
                    $data_tmp['user_name'] = $result['nhansu'][0]['nhansu_lastname'].' '.$result['nhansu'][0]['nhansu_firstname'];
                }else{
                    $data_tmp['user_name'] = '';
                }
                $data_ketqua[] = $data_tmp;
            }
        }
        $result['draw'] = $draw;
        $result['recordsTotal'] = $count_all;
        $result['recordsFiltered'] = $count_list;
        $result['data'] = $data_ketqua;
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit;
    }
    function detail(){
        $ketqua_id = $this->input->get('ketqua');
        $hopdong_ketqua = $this->ketqua->get_hopdong_ketqua($ketqua_id);
        $list_ketqua_duyet = $this->ketqua->get_ketqua_duyet($ketqua_id);
        $duyet_latest = end(array_values($list_ketqua_duyet));
        // Get file result latest
        $file_result = $this->upload_url.'result/'.$hopdong_ketqua['hopdong_code'].'-'.$ketqua_id.'-'.$duyet_latest['duyet_id'].'.pdf';
        // Get lits phanhoi
        $list_phanhoi = $this->phanhoi->get_phanhoi_ketqua($ketqua_id, $this->user_login);
        if($list_phanhoi){
            foreach ($list_phanhoi as &$phanhoi){
                $phanhoi['approve_info'] = $this->phanhoi_approve_txt[$phanhoi['phanhoi_approve']];
            }
        }
        $data = array(
            'ketqua_id' => $ketqua_id,
            'file_result' => $file_result,
            'list_phanhoi' => $list_phanhoi
        );
        $this->parser->assign('data', $data);
        $this->parser->parse('ketqua/detail');
    }
    function phanhoi(){
        $ketqua_id = $this->input->get('ketqua_id');
        $ketqua = $this->ketqua->get_ketqua_id($ketqua_id);
        $data = array(
            'ketqua' => $ketqua,
            'result' => $this->input->get('result')
        );
        $data_post = $this->input->post();
        if($ketqua && $data_post){
            // Add phanhoi
            $phanhoi_id = $this->phanhoi->insert_phanhoi(array(
                'ketqua_id' => $ketqua_id,
                'phanhoi_content' => $data_post['phanhoi_content'],
                'contact_id' => $this->user_login,
                'phanhoi_date' => date("Y-m-d H:i:s")
            ));
            $name_file = $_FILES["phanhoi_file"]["name"];
            if($phanhoi_id && $name_file){
                $ext = pathinfo($name_file , PATHINFO_EXTENSION);;
                $file_name_save = "phanhoi_" . $ketqua_id . '_' . $phanhoi_id . "." . $ext;
                $config['upload_path'] = $this->upload_path . 'phanhoi/';
                $config['allowed_types'] = 'doc|docx|pdf';
                $config['file_name'] = $file_name_save;
				if(!is_dir($config['upload_path'])){
                    mkdir($config['upload_path'], 0755, true);
                }
                $this->load->library('upload', $config);
                if($this->upload->do_upload('phanhoi_file')){
                    $this->phanhoi->update_phanhoi_file(array(
                        'phanhoi_file' => $file_name_save,
                        'phanhoi_id' => $phanhoi_id
                    ));
                };
                
            }
            redirect(site_url().'customer/ketqua/phanhoi?ketqua_id='.$ketqua_id.'&result=true');
        }
        $this->parser->assign('data', $data);
        $this->parser->parse('ketqua/phanhoi');
    }
    private function get_hopdong_chitieu($hopdong_id, $list_chitieu){
        $hopdong = $this->hopdong->get_hopdong_id($hopdong_id);
        if($hopdong && count($list_chitieu) > 0){
            // Call API congty
            $this->curl->create($this->api_khachhang_url.'list_congty');
            $this->curl->post(array(
                'congty_id' => trim($hopdong['congty_id'])
            ));
            $result = json_decode($this->curl->execute(), TRUE);
            if($result['err_code'] == 200){
                $hopdong['congty'] = $result['list_congty'][0];
            }
            // Get mau
            $list_mau = $this->hopdong->get_mau_list_chitieu($list_chitieu);
            if($list_mau){
                if(count($list_mau) > 1){

                }else{
                    $hopdong['mau'] = $list_mau[0];
                }
            }
            // Get list chitieu
            $list_chitieu_info = $this->hopdong->get_chitieu_list_id($list_chitieu);
            if($list_chitieu_info){
                $date_end = '';
                $chitieu_export = array();
                $list_congnhan = array();
                foreach ($list_chitieu_info as $chitieu_info){
                    // Get dateend max
                    if(!$date_end || $date_end < $chitieu_info['chitieu_dateend']){
                        $date_end = $chitieu_info['chitieu_dateend'];
                    }
                    // Get chat info
                    $list_chat = $this->hopdong->get_list_chat_id(json_decode($chitieu_info['list_chat'], TRUE), $chitieu_info['dongia_id']);
                    $list_ketqua = json_decode($chitieu_info['list_ketqua'], TRUE);
                    if($list_chat){
                        foreach ($list_chat as $chat){
                            // Get congnhan chat
                            $list_congnhan_chat = $this->nenmau->get_congnhan_chat($chat['chat_id']);
                            //var_dump($congnhan);
                            if($list_congnhan_chat){
                                foreach ($list_congnhan_chat as $congnhan_chat){
                                    $list_congnhan[$congnhan_chat['congnhan_id']] = $congnhan_chat;
                                }
                            }
                            // Create chitieu export
                            $chitieu_export[] = array(
                                'chat_name' => $chat['chat_name'],
                                'chat_ketqua' => $list_ketqua[$chat['chat_id']],
                                'capacity' => $chat['capacity'],
                                'val_min' => $chat['val_min'],
                                'val_max' => $chat['val_max'],
                                'donvitinh' => $chitieu_info['donvitinh_name'],
                                'phuongphap' => $chitieu_info['phuongphap_name'],
                                'congnhan' => $list_congnhan_chat
                            );
                        }
                    }
                }
                $hopdong['date_end'] = $date_end;
                $hopdong['list_chitieu'] = $chitieu_export;
                $hopdong['list_chitieu_info'] = $list_chitieu_info;
                $hopdong['list_congnhan'] = $list_congnhan;
            }
        }
        return $hopdong;
    }
	function test(){
		$config['upload_path'] = $this->upload_path . 'phanhoi/';
		var_dump($config['upload_path']);
		var_dump(scandir($this->upload_path));
		if(!is_dir($config['upload_path'])){
			var_dump(mkdir($config['upload_path'], 0755, true));
		}
	}
}