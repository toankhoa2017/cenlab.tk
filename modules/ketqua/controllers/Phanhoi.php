<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Phanhoi extends ADMIN_Controller {
    private $api_nhansu_url = 'http://dev.cenlab.vn/nhansu/api/';
    private $api_luumau_url = 'http://dev.cenlab.vn/luumau/api/';
    private $api_khachhang_url = 'http://dev.cenlab.vn/khachhang/api/';
    private $ptn_id;
    private $user_login = false;
    private $upload_path;
    private $upload_url;
    private $phanhoi_approve_txt = array(
        '0' => array('label' => 'Đang chờ duyệt', 'icon' => '<i class="fa fa-clock-o orange" aria-hidden="true"></i>', 'class' => 'orange'),
        '1' => array('label' => 'Đồng ý', 'icon' => '<i class="fa fa-check-circle green" aria-hidden="true"></i>', 'class' => 'green'),
        '2' => array('label' => 'Không đồng ý', 'icon' => '<i class="fa fa-times-circle red" aria-hidden="true"></i>', 'class' => 'red')
    );
    function __construct() {
        parent::__construct();
        $this->load->library('curl');
        $this->load->model('mod_hopdong', 'hopdong');
        $this->load->model('mod_phongthinghiem', 'phongthinghiem');
        $this->load->model('mod_ketqua', 'ketqua');
        $this->load->model('mod_phanhoi', 'phanhoi');
        $this->load->model('mod_suco', 'suco');
        // Set ptn id
        if(ENVIRONMENT === 'local'){
            $this->ptn_id = 2;
            $this->user_login = 7;
            $this->upload_path = '_uploads/';
            $this->upload_url = base_url().'_uploads/';
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
            $this->api_khachhang_url = $this->_api['khachhang'];
            $this->upload_path = _UPLOADS_PATH;
            $this->upload_url = base_url()._UPLOADS_URL;
        }
    }
    function index(){
        $this->parser->parse('phanhoi/index');
    }
    function detail(){
        $phanhoi_id = $this->input->get('phanhoi_id');
        $phanhoi_info = $this->phanhoi->get_phanhoi_info($phanhoi_id);
        $list_mau = false;
        if($phanhoi_info){
            // Check file upload
            if(file_exists($this->upload_path . 'phanhoi/'.$phanhoi_info['phanhoi_file'])){
                $phanhoi_info['phanhoi_file_url'] = $this->upload_url.'phanhoi/'.$phanhoi_info['phanhoi_file'];
            }
            // Call API get contact info
            $this->curl->create($this->api_khachhang_url.'list_contact');
            $this->curl->post(array(
                'contact_id' => trim($phanhoi_info['contact_id'])
            ));
            $result = json_decode($this->curl->execute(), TRUE);
            $phanhoi_info['contact_info'] = $result['err_code'] == 200?$result['list_contact'][0]:false;
            // Get ketqua chi tiet
            $ketqua_chitiet = $this->ketqua->get_ketqua_chitiet($phanhoi_info['ketqua_id']);
            // Get mau
            $list_mau = $this->hopdong->get_mau_list_chitieu(array_column($ketqua_chitiet,'mauchitiet_id'));
            if($list_mau){
                foreach ($list_mau as &$mau){
                    $list_chitieu = $this->hopdong->get_chitieu_mau($mau['mau_id']);
                    $list_chitieu_info = array();
                    if($list_chitieu){
                        foreach ($list_chitieu as &$chitieu_info){
                            if(in_array($chitieu_info['mauchitiet_id'], array_column($ketqua_chitiet,'mauchitiet_id'))){
                                $chitieu_info['list_ketqua'] = json_decode($chitieu_info['list_ketqua'], TRUE);
                                $chitieu_info['list_chat_info'] = $this->phongthinghiem->get_list_chat_info(json_decode($chitieu_info['list_chat'], TRUE));
                                $list_chitieu_info[] = $chitieu_info;
                            }
                        }
                    }
                    $mau['list_chitieu_info'] = $list_chitieu_info;
                }
            }
            /*
            echo '<pre>';
            var_dump($list_mau);
            echo '</pre>';
            exit;*/
        }
        $data = array(
            'phanhoi' => $phanhoi_info,
            'list_mau' => $list_mau,
            'result' => $this->input->get('result')
        );
        $this->parser->assign('data', $data);
        $this->parser->parse('phanhoi/detail');
    }
    function ajax_list_phanhoi(){
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
        // Count list all suco
        $count_all_suco = $this->phanhoi->count_all_phanhoi();
        // Count list search suco
        $count_list_suco = $this->phanhoi->count_list_phanhoi($search);
        // Get list certificate
        $list_phanhoi = $this->phanhoi->get_list_phanhoi($search, $sort_column, $sort_direction, $start, $length);
        // Build data
        foreach ($list_phanhoi as &$phanhoi_info){
            // Set index
            $phanhoi_info['index'] = ++$start;
            // Format date
            $phanhoi_info['phanhoi_date'] = date("d/m/Y H:i:s", strtotime($phanhoi_info['phanhoi_date']));
            // Status suco
            $phanhoi_info['phanhoi_approve_info'] = $this->phanhoi_approve_txt[$phanhoi_info['phanhoi_approve']];
            // Call API get contact info
            $this->curl->create($this->api_khachhang_url.'list_contact');
            $this->curl->post(array(
                'contact_id' => trim($phanhoi_info['contact_id'])
            ));
            $result = json_decode($this->curl->execute(), TRUE);
            $phanhoi_info['contact_info'] = $result['err_code'] == 200?$result['list_contact'][0]:false;
            // Add link detail
            $phanhoi_info['link_detail'] = site_url().'ketqua/phanhoi/detail?phanhoi_id='.$phanhoi_info['phanhoi_id'];
        }
        $result['draw'] = $draw;
        $result['recordsTotal'] = $count_all_suco;
        $result['recordsFiltered'] = $count_list_suco;
        $result['data'] = $list_phanhoi;
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit;
    }
    function ajax_approve(){
        $output = array();
        $data = $this->input->post();
        // Get phanhoi info
        $phanhoi_info = $this->phanhoi->get_phanhoi_info($data['phanhoi_id']);
        if($phanhoi_info && in_array($data['phanhoi_approve'], array('1','2'))){
            //== Not accept phanhoi
            if($data['phanhoi_approve'] == '2'){
                $update = $this->phanhoi->update_phanhoi_approve(array(
                    'phanhoi_id' => $data['phanhoi_id'],
                    'phanhoi_approve' => $data['phanhoi_approve'],
                    'phanhoi_approve_user' => $this->user_login,
                    'phanhoi_approve_note' => $data['approve_content'],
                    'phanhoi_approve_date' => date("Y-m-d H:i:s")
                ));
                if($update){
                    $output['code'] = 1;
                    $output['message'] = 'Duyệt phản hồi thành công';
                }else{
                    $output['code'] = 0;
                    $output['message'] = 'Không cập nhật được dữ liệu, vui lòng thử lại.';
                }
            }
            //== Accept phanhoi
            else{
                // Thay đổi thông tin hopdong
                if(in_array('1', $data['phanhoi_type'])){
                    // Get hopdong info
                    $hopdong_info = $this->ketqua->get_hopdong_ketqua($ketqua_id);
                    // Insert suco
                    $this->suco->insert_suco(array(
                        'suco_type' => 1,
                        'suco_content' => $data['approve_content'],
                        'hopdong_id' => $phanhoi_info['hopdong_id'],
                        'nhansu_id' => $this->user_login,
                        'suco_createdate' => date("Y-m-d H:i:s")                        
                    ));
                }
                // Thay đổi thông tin chitieu
                if(in_array('2', $data['phanhoi_type'])){
                    foreach ($data['chitieu_select'] as $mauketqua_id){
                        $insert_mauketqua_duyet = $this->ketqua->insert_mauketqua_duyet(array(
                            'mauketqua_id' => $mauketqua_id,
                            'user_create' => $this->user_login,
                            'date_create' => date("Y-m-d H:i:s"),
                            'mauketqua_note' => $data['approve_content'],
                        ));
                        if($insert_mauketqua_duyet){
                            $this->ketqua->update_mauketqua_approve(array(
                                'mauketqua_duyet_id' => $insert_mauketqua_duyet,
                                'mauketqua_id' => $mauketqua_id
                            ));
                        }
                    }
                }
                // Update status phieuketqua = 3 (Đang sửa đổi)
                $this->ketqua->update_approve(array(
                    'ketqua_id' => $phanhoi_info['ketqua_id'],
                    'duyet_result' => 3
                ));
                // Update phanhoi
                $update = $this->phanhoi->update_phanhoi_approve(array(
                    'phanhoi_id' => $data['phanhoi_id'],
                    'phanhoi_approve' => $data['phanhoi_approve'],
                    'phanhoi_approve_user' => $this->user_login,
                    'phanhoi_approve_note' => $data['approve_content'],
                    'phanhoi_approve_date' => date("Y-m-d H:i:s")
                ));
                if($update){
                    $output['code'] = 1;
                    $output['message'] = 'Duyệt phản hồi thành công';
                }else{
                    $output['code'] = 0;
                    $output['message'] = 'Không cập nhật được dữ liệu, vui lòng thử lại.';
                }
            }
        }else{
            $output['code'] = 0;
            $output['message'] = 'Dữ liệu không đúng, vui lòng thử lại.';
        }
        echo json_encode($output);
    }
}