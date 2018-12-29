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
        $this->parser->parse('suco/index');
    }
    function detail(){
        $suco_id = $this->input->get('suco');
        $suco_info = $this->suco->get_suco($suco_id);
        if($suco_info){
            // Get suco chitiet
            $list_suco_chitiet = $this->suco->get_suco_chitieu($suco_info['suco_id']);
            if($list_suco_chitiet){
                foreach ($list_suco_chitiet as &$suco_chitiet){
                    // Get chat info
                    $list_chat = $this->phongthinghiem->get_list_chat_info(json_decode($suco_chitiet['list_chat'], TRUE));
                    if($list_chat){
                        $suco_chitiet['list_chat_info'] = $list_chat;
                    }
                }
            }
            $suco_info['list_suco_chitiet'] = $list_suco_chitiet;
            // Status suco
            $suco_info['suco_approve_info'] = $this->suco_approve_txt[$suco_info['suco_approve']];
            // Format date
            $suco_info['suco_createdate'] = date("d/m/Y H:i:s", strtotime($suco_info['suco_createdate']));
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
        // Save approve
        $data = $this->input->post();
        if($data){
            $update_suco = $this->suco->update_suco(array(
                'suco_id' => $suco_id,
                'suco_approve' => $data['suco_approve'],
                'user_approve_id' => $this->user_login,
                'approve_note' => '',
                'approve_date' => date("Y-m-d H:i:s")
            ));
            if($update_suco){
                redirect(site_url().'phongthinghiem/suco/detail?suco='.$suco_id.'&result=true');
            }
        }
        $data = array(
            'suco' => $suco_info,
            'result' => $this->input->get('result')
        );
        $this->parser->assign('data', $data);
        $this->parser->parse('suco/detail');
    }
    function ajax_list_suco(){
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
        $count_all_suco = $this->suco->count_all_suco($this->ptn_id);
        // Count list search suco
        $count_list_suco = $this->suco->count_list_suco($search, $this->ptn_id);
        // Get list certificate
        $list_suco = $this->suco->get_list_suco($search, $sort_column, $sort_direction, $start, $length, $this->ptn_id);
        // Build data
        foreach ($list_suco as &$suco_info){
            // Set index
            $suco_info['index'] = ++$start;
            // Get suco chitiet
            $list_suco_chitiet = $this->suco->get_suco_chitieu($suco_info['suco_id']);
            if($list_suco_chitiet){
                foreach ($list_suco_chitiet as &$suco_chitiet){
                    // Get chat info
                    $list_chat = $this->phongthinghiem->get_list_chat_info(json_decode($suco_chitiet['list_chat'], TRUE));
                    if($list_chat){
                        $suco_chitiet['list_chat_info'] = $list_chat;
                    }
                }
            }
            $suco_info['suco_chitieu'] = $list_suco_chitiet;
            // Status suco
            $suco_info['suco_approve_info'] = $this->suco_approve_txt[$suco_info['suco_approve']];
            // Format date
            $suco_info['suco_createdate'] = date("d/m/Y H:i:s", strtotime($suco_info['suco_createdate']));
        }
        $result['draw'] = $draw;
        $result['recordsTotal'] = $count_all_suco;
        $result['recordsFiltered'] = $count_list_suco;
        $result['data'] = $list_suco;
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit;
    }
}