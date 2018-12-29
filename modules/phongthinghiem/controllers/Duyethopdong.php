<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Duyethopdong extends ADMIN_Controller {
    private $api_khachhang_url = 'http://dev.cenlab.vn/khachhang/api/';
    private $api_nhansu_url = 'http://dev.cenlab.vn/nhansu/api/';
    private $ptn_id;
    private $user_login = false;
    private $ketqua_txt = array(
        '0' => array('label' => 'Đang chờ duyệt', 'icon' => '<i class="fa fa-clock-o orange" aria-hidden="true"></i>', 'class' => 'tooltip-warning'),
        '1' => array('label' => 'Đồng ý', 'icon' => '<i class="fa fa-check-circle green" aria-hidden="true"></i>', 'class' => 'tooltip-success'),
        '2' => array('label' => 'Không đồng ý', 'icon' => '<i class="fa fa-times-circle red" aria-hidden="true"></i>', 'class' => 'tooltip-error'),
        //'3' => array('label' => 'Phân tích lại', 'icon' => '<i class="fa fa-pencil-square orange" aria-hidden="true"></i>', 'class' => 'tooltip-warning')
    );
    private $hopdong_approve = array(
        '0' => 'Chưa duyệt',
        '1' => 'Đồng ý',
        '2' => 'Hủy hợp đồng',
        '3' => 'Sửa hợp đồng',
        '4' => 'PTN duyệt'
    );
    function __construct() {
        parent::__construct();
        $this->load->library('curl');
        $this->load->model('mod_hopdong', 'hopdong');
        $this->load->model('mod_phongthinghiem', 'phongthinghiem');
        // Set ptn id
        if(ENVIRONMENT === 'local'){
            $this->ptn_id = 3;
            $this->user_login = 2;
        }else{
            $this->user_login = $this->session->userdata()['ssAdminId'];
            if(!$this->user_login){
                    redirect(site_url().'admin/login');
            }
            $this->api_khachhang_url = $this->_api['khachhang'];
            $this->api_nhansu_url = $this->_api['nhansu'];
            $this->upload_path = _UPLOADS_PATH;
            $this->upload_url = base_url()._UPLOADS_URL;
        }
    }
    function index(){
        // Get type
        $approved = $this->input->get('approved')?$this->input->get('approved'):0;
        $data = array(
            'approved' => $approved
        );
        // Check permission
        /*
        $permission = $this->permarr;
        if($permission && isset($permission[_NM_DUYETHOPDONG]) && ($permission[_NM_DUYETHOPDONG]['write'] || $permission[_NM_DUYETHOPDONG]['master'])){
            $data['get_all'] = true;
        }else{
            $data['error'] = 'Không có quyền duyệt Hợp đồng, Vui lòng liên hệ Admin để được cấp quyền.';
        }*/
        $this->parser->assign('data', $data);
        $this->parser->parse('duyethopdong/index');
    }
    function detail(){
        $hopdong_id = $this->input->get('hopdong');
        // Check permission
        $duyet_hopdong = false;
        $permission = $this->permarr;
        if($permission && isset($permission[_NM_DUYETHOPDONG]) && ($permission[_NM_DUYETHOPDONG]['write'] || $permission[_NM_DUYETHOPDONG]['master'])){
            $duyet_hopdong = true;
        }
        $hopdong = $this->get_hopdong($hopdong_id);
        $data = array(
            'hopdong' => $hopdong,
            'edit' => $this->input->get('edit'),
            'approve' => $this->input->get('approve'),
            'duyet_hopdong' => $duyet_hopdong
        );
        $this->parser->assign('data', $data);
        $this->parser->parse('duyethopdong/detail');
    }
    private function get_hopdong($hopdong_id){
        $hopdong = $this->hopdong->get_hopdong_info($hopdong_id);
        if($hopdong){
            // Get hopdong approve
            $hopdong['hopdong_approve_txt'] = $this->hopdong_approve[$hopdong['hopdong_approve']];
            
            // Call API user create
            $this->curl->create($this->api_nhansu_url.'nhansu_info');
            $this->curl->post(array(
                'nhansu_id' => trim($hopdong['nhansu_id'])
            ));
            $result = json_decode($this->curl->execute(), TRUE);
            if($result['err_code'] == 200){
                $hopdong['nhansu'] = $result['nhansu'][0];
            }
            
            // Get mau of hopdong
            $list_mau = $this->hopdong->get_mau_hopdong($hopdong['hopdong_id']);
            if($list_mau){
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
                            $chitieu['list_chat'] = $list_chat;
                            // LOD_LOQ
                            $chitieu['lod_loq_txt'] = $chitieu['lod_loq']=='1'?'LOD':$chitieu['lod_loq']=='2'?'LOQ':'LOD & LOQ';
                        }
                    }
                    $mau['list_chitieu'] = $list_chitieu?$list_chitieu:false;
                }
                $hopdong['list_mau'] = $list_mau;
            }
            // Download hopdong
            $hopdong['file_download_word'] = $this->upload_url.'hopdong/'.$hopdong['hopdong_code'].'.docx';
            $hopdong['file_download_pdf'] = $this->upload_url.'hopdong/'.$hopdong['hopdong_code'].'.pdf';
        }
        return $hopdong;
    }
    function ajax_list(){
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
        // Get hopdong_mau
        $hopdong_mau = $data['hopdong_mau']?TRUE:FALSE;
        // Get list for user or all for duyet
        $get_for = $this->user_login;
        // Check permission
        $get_for = $data['get_all'];
        /*
        $permission = $this->permarr;
        if(isset($data['get_all']) && $data['get_all'] === 'all'){
            if($permission && isset($permission[_NM_DUYETHOPDONG]) && ($permission[_NM_DUYETHOPDONG]['write'] || $permission[_NM_DUYETHOPDONG]['master'])){
                $get_for = $data['get_all'];
            }
        }else{
            if(!$permission || !isset($permission[_NM_PHIEUYEUCAU]) || 
                (!$permission[_NM_PHIEUYEUCAU]['read'] && !$permission[_NM_PHIEUYEUCAU]['write'] && !$permission[_NM_PHIEUYEUCAU]['master'])){
                echo json_encode(array('error' => 'Không có quyền truy cập'), JSON_UNESCAPED_UNICODE);
                exit;
            }
        }*/
        // Get type for duyet
        $approved = $this->input->get('approved')?true:false;
        // Count list all hopdong
        $count_all_hopdong = $this->hopdong->count_all_hopdong($approved, $this->user_login);
        // Count list search hopdong
        $count_list_hopdong = $this->hopdong->count_list_hopdong($approved, $this->user_login);
        // Get list certificate
        $list_hopdong = $this->hopdong->get_list_hopdong($approved, $this->user_login, $search, $sort_column, $sort_direction, $start, $length);
        // Build data        
        $data_hopdong = array();
        foreach ($list_hopdong as $hopdong){
            $hopdong['action'] = '';
            //$data_hopdong[] = array_values($hopdong);
            $data_tmp = array();
            $data_tmp['index'] = ++$start;
            foreach ($hopdong as $key=>$value){
                $data_tmp[$key] = $value;
                if($key == 'congty_id'){
                    // Call API congty
                    $this->curl->create($this->api_khachhang_url.'list_congty');
                    $this->curl->post(array(
                        'congty_id' => trim($value)
                    ));
                    $result = json_decode($this->curl->execute(), TRUE);
                    $data_tmp['congty_name'] = null;
                    if($result['err_code'] == 200){
                        $data_tmp['congty_name'] = $result['list_congty'][0]['congty_name'];
                    }
                }
                if($key == 'contact_id'){
                    // Call API contact
                    $this->curl->create($this->api_khachhang_url.'list_contact');
                    $this->curl->post(array(
                        'contact_id' => trim($value)
                    ));
                    $result = json_decode($this->curl->execute(), TRUE);
                    $data_tmp['contact_name'] = null;
                    if($result['err_code'] == 200){
                        $data_tmp['contact_name'] = $result['list_contact'][0]['contact_fullname'];
                    }
                }
                if($key == 'hopdong_approve'){
                    $data_tmp['hopdong_approve_txt'] = $this->hopdong_approve[$value];
                }
            }
            // Get edit request
            $suahopdong = $this->hopdong->get_edit_request($hopdong['hopdong_id']);
            $data_tmp['total_edit_request'] = 0;
            $data_tmp['edit_request_not_approve'] = 0;
            if($suahopdong){
                $data_tmp['total_edit_request'] = count($suahopdong);
                $data_tmp['edit_request_not_approve'] = array_count_values(array_column($suahopdong, 'suahopdong_approve'))['0'];
            }
            // Add link detail
            $data_tmp['link_detail'] = site_url().'phongthinghiem/duyethopdong/detail?hopdong='.$hopdong['hopdong_id'];
            $data_hopdong[] = $data_tmp;
        }
        $result['draw'] = $draw;
        $result['recordsTotal'] = $count_all_hopdong;
        $result['recordsFiltered'] = $count_list_hopdong;
        $result['data'] = $data_hopdong;
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit;
    }
    function ajax_approve(){
        $output = array();
        $hopdong_id = $this->input->post('hopdong_id');
        $hopdong_approve = $this->input->post('hopdong_approve');
        $duyet_content = $this->input->post('duyet_content');
        $hopdong = $this->hopdong->get_hopdong_id($hopdong_id);
        if($hopdong){
            if($hopdong_approve == '2'){
                $this->hopdong->disable_hopdong($hopdong_id);
                $this->hopdong->enable_hopdong($hopdong['hopdong_idparent']);
            }
            // Add new hopdong approve
            $approve_id = $this->hopdong->insertApprove(array(
                'duyet_content' => $duyet_content,
                'duyet_user_id' => $this->user_login,
                'hopdong_approve' => $hopdong_approve,
                'hopdong_id' => $hopdong_id
            ));
            // Update hopdong approve
            if($approve_id){
                $update_hopdong = $this->hopdong->updateApproveHopdong($hopdong_id, $approve_id);
                if($update_hopdong){
                    $output['code'] = 1;
                    $output['message'] = 'Duyệt thành công';
                }else{
                    $output['code'] = 0;
                    $output['message'] = 'Không cập nhật được hợp đồng';
                }
            }else{
                $output['code'] = 0;
                $output['message'] = 'Không cập nhật được dữ liệu duyệt hợp đồng';
            }
        }else{
            $output['code'] = 0;
            $output['message'] = 'Hợp đồng không tồn tại';
        }
        echo json_encode($output);
    }
}