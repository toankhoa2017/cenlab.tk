<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ketqua extends ADMIN_Controller {
    private $api_khachhang_url = 'http://dev.cenlab.vn/khachhang/api/';
    private $api_nhansu_url = 'http://dev.cenlab.vn/nhansu/api/';
    private $api_general_url = 'http://dev.cenlab.vn/general/api/';
    private $hopdong_approve = array(
        '0' => 'Chưa duyệt',
        '1' => 'Đồng ý',
        '2' => 'Hủy hợp đồng',
        '3' => 'Sửa hợp đồng',
        '4' => 'PTN duyệt'
    );
    private $ptn_id;
    private $user_login = false;
    private $upload_path;
    private $upload_url;
    function __construct() {
        parent::__construct();
        $this->load->library('curl');
        $this->load->model('mod_nenmau', 'nenmau');
        $this->load->model('mod_hopdong', 'hopdong');
        $this->load->model('mod_phongthinghiem', 'phongthinghiem');
        $this->load->model('mod_ketqua', 'ketqua');
        $this->load->model('mod_suco', 'suco');
        // Set ptn id
        if(ENVIRONMENT === 'local'){
            $this->ptn_id = 2;
            $this->upload_path = '_uploads/';
            $this->upload_url = base_url().'_uploads/';
        }else{
            $this->ptn_id = $this->session->userdata()['ssAdminDonvi'];
            if(!$this->ptn_id){
                redirect(site_url().'admin/login');
            }
            $this->upload_path = _UPLOADS_PATH;
            $this->upload_url = base_url()._UPLOADS_URL;
        }
        // Set user login
        if(ENVIRONMENT === 'local'){
            $this->user_login = 2;
            
            define('_TKQ_PHIEUKQ', 68);
            define('_TKQ_DANHSACHKQ', 69);
            
            $this->permarr = array(
                68 => array('write' => 'checked', 'update' => 'checked'),
                69 => array('write' => 'checked', 'update' => 'checked', 'master' => 'checked'),
            );
        }else{
            $this->user_login = $this->session->userdata()['ssAdminId'];
            if(!$this->user_login){
                redirect(site_url().'admin/login');
            }
            $this->api_khachhang_url = $this->_api['khachhang'];
            $this->api_nhansu_url = $this->_api['nhansu'];
            $this->api_general_url = $this->_api['general'];
        }
    }
    function danhsachketqua(){
        // Check permission
        $permission = $this->permarr;
        if(!$permission || !isset($permission[_TKQ_DANHSACHKQ]) || 
            (!$permission[_TKQ_DANHSACHKQ]['read'] && !$permission[_TKQ_DANHSACHKQ]['write'] && !$permission[_TKQ_DANHSACHKQ]['update'] && !$permission[_TKQ_DANHSACHKQ]['master'])){
            redirect(site_url().'admin/denied?w=read');
            exit;
        }
        $this->parser->parse('ketqua/danhsachketqua');
    }
    function phieuketqua(){
        // Check permission
        $permission = $this->permarr;
        if(!$permission || !isset($permission[_TKQ_PHIEUKQ]) || 
            (!$permission[_TKQ_PHIEUKQ]['write'] && !$permission[_TKQ_PHIEUKQ]['master'])){
            redirect(site_url().'admin/denied?w=write');
            exit;
        }
        $this->parser->parse('ketqua/phieuketqua');
    }
    function create(){
        $result_compare = array(
            'less' => array('Không phát hiện', 'ND', 'Âm tính', '(-)'),
            'between' => array('Hiển thị số', 'Phát hiện', 'Vết', 'Dương tính', '(+)', 'compare_loq' => '< LOQ'),
            'more' => array('Hiển thị số', 'Dương tính', 'POS', '(+)'),
        );
        $matong_list = array('13C Isotope Analysis C4 Sugar in Honey');
        $hopdong_id = $this->input->get('hopdong');
        $list_chitieu = $this->input->get('chitieu');
        $result_template = $this->input->get('result_template')?$this->input->get('result_template'):1;
        $error = $hopdong = false;
        // Get hopdong
        if($hopdong_id && $list_chitieu && count($list_chitieu) > 0){
            // Check chitieu ketqua
            $list_ketqua = $this->ketqua->get_ketqua_chitieu($list_chitieu);
            if($list_ketqua && count($list_ketqua) > 0){
                $error = 'Chỉ tiêu đã xuất kết quả';
            }else{
                switch ($result_template){
                    case '1':
                    case '3':
                    case '4':
                    case '6':
                    case '7':
                        $hopdong = $this->get_hopdong_chitieu($hopdong_id, $list_chitieu);
                        break;
                    case '2':
                        $hopdong = $this->get_hopdong_mau($hopdong_id, $list_chitieu);
                        break;
                }
            }
        }else{
            $error = 'Chưa chọn chỉ tiêu để xuất kết quả';
        }
        // Call API get uesr duyet
        $user_duyet = false;
        $this->curl->create($this->api_nhansu_url.'duyetketqua');
        $this->curl->post(array(
            'nhansu_id' => trim($this->user_login)
        ));
        $result = json_decode($this->curl->execute(), TRUE);
        if($result['err_code'] == 200){
            foreach ($result['list'] as $list_user_duyet){
                $user_duyet = $list_user_duyet;
                break;
            }
        }
        // var_dump($hopdong['list_chitieu']);
        /*
         * Using debug
         * 
         *
        if($this->input->post()){
            echo '<pre>';
            var_dump($this->input->post('between_select'));
            $struct_visinh = '/^(.+)x(.+)\^(.+)$/';
            foreach ($hopdong['list_chitieu'] as $index => $chitieu){

                $base_rs = $exp_rs = $ketqua_convert = '';
                if (preg_match($struct_visinh, $chitieu['chat_ketqua'], $match)){
                    $base_rs = $match[1].' x '.$match[2];
                    $exp_rs = $match[3];
                    $ketqua_convert = $match[1] * pow($match[2], $match[3]);
                }else{
                    $base_rs = $chitieu['chat_ketqua'];
                    $exp_rs = '';
                    $ketqua_convert = $chitieu['chat_ketqua'];
                }

                if($chitieu['capacity'] == 1 && is_numeric($ketqua_convert)
                        && ((isset($chitieu['val_min']) && is_numeric($chitieu['val_min'])) 
                        || (isset($chitieu['val_max']) && is_numeric($chitieu['val_max'])))){
                    // So sánh LOD
                    if($chitieu['lod_loq'] == '1' && $chitieu['val_min']){
                        if(floatval($ketqua_convert) < floatval($chitieu['val_min'])){
                            $ketqua_compare = $this->input->post('less_select');
                            if($ketqua_compare !== FALSE){
                                $base_rs = $result_compare['less'][$ketqua_compare];
                                $exp_rs = '';
                            }
                        }else{
                            $ketqua_compare = $this->input->post('more_select');
                            if($ketqua_compare){
                                $base_rs = $result_compare['more'][$ketqua_compare];
                                $exp_rs = '';
                            }
                            
                        }
                    }
                    // So sánh LOQ
                    if($chitieu['lod_loq'] == '2' && $chitieu['val_max']){
                        if(floatval($ketqua_convert) < floatval($chitieu['val_max']) ){
                            $ketqua_compare = $this->input->post('less_select');
                            if($ketqua_compare !== FALSE){
                                $base_rs = $result_compare['less'][$ketqua_compare];
                                $exp_rs = '';
                            }
                        }else{
                            $ketqua_compare = $this->input->post('more_select');
                            if($ketqua_compare){
                                $base_rs = $result_compare['more'][$ketqua_compare];
                                $exp_rs = '';
                            }
                        }
                    }
                    // So sánh LOD, LOQ
                    if($chitieu['lod_loq'] == '3'){
                        if($chitieu['val_min'] && floatval($ketqua_convert) < floatval($chitieu['val_min'])){
                            $ketqua_compare = $this->input->post('less_select');
                            if($ketqua_compare !== FALSE){
                                $base_rs = $result_compare['less'][$ketqua_compare];
                                $exp_rs = '';
                            }
                        }elseif($chitieu['val_max'] && floatval($ketqua_convert) >= floatval($chitieu['val_max'])){
                            $ketqua_compare = $this->input->post('more_select');
                            if($ketqua_compare){
                                $base_rs = $result_compare['more'][$ketqua_compare];
                                $exp_rs = '';
                            }
                        }elseif(
                                $chitieu['val_min'] && $chitieu['val_max'] && 
                                floatval($ketqua_convert) >= floatval($chitieu['val_min'])  && 
                                floatval($ketqua_convert) < floatval($chitieu['val_max'])
                        ){
                            $ketqua_compare = $this->input->post('between_select');
                            if($ketqua_compare){
                                if($ketqua_compare == 'compare_loq'){
                                    $base_rs = '< '.$chitieu['val_max'];
                                    $exp_rs = '';
                                }else{
                                    $base_rs = $result_compare['between'][$ketqua_compare];
                                    $exp_rs = '';
                                }
                            }
                        }
                    }
                }
                var_dump($base_rs);
            }
            echo '</pre>';
            exit;
        }*/
        // Process ketqua
        $date_export = date("Y-m-d H:i:s");
        if(!$error && $this->input->post()){
            $user_duyet_id = $this->input->post('user_duyet');
            if($user_duyet_id){
                // Create ketqua
                $ketqua_id = $this->ketqua->insert_ketqua(array(
                    'ketqua_note' => trim($this->input->post('ketqua_note')),
                    'date_export' => $date_export,
                    'user_id' => $this->user_login
                ));
                // Create ketqua_chitiet
                if($ketqua_id){
                    $ketqua_chitiet = array();
                    foreach ($hopdong['list_chitieu_info'] as $chitieu_info){
                        $ketqua_chitiet[] = array(
                            'ketqua_id' => $ketqua_id,
                            'mauchitiet_id' => $chitieu_info['mauchitiet_id']
                        );
                    }
                    $ketqua_chitiet = $this->ketqua->insert_ketqua_chitiet($ketqua_chitiet);
                }
                // Create ketqua_duyet
                $ketqua_duyet = false;
                if($ketqua_id){
                    $ketqua_duyet = $this->ketqua->insert_ketqua_duyet(array(
                        'ketqua_id' => $ketqua_id,
                        'user_send' => $this->user_login,
                        'user_receive' => $user_duyet_id
                    ));
                }
                if($ketqua_duyet){
                    $templateProcessor = false;
                    $struct_visinh = '/^(.+)x(.+)\^(.+)$/';
                    switch ($result_template){
                        case '1':
                            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($this->upload_path.'result_template/BM.15.05a.docx');
                            $templateProcessor->setValue('mau_code', htmlspecialchars($hopdong['mau']['mau_code']));
                            $templateProcessor->setValue('hopdong_code', htmlspecialchars($hopdong['hopdong_code']));
                            $templateProcessor->setValue('date_print', date_format(date_create($date_export), 'd/m/Y'));
                            $templateProcessor->setValue('congty_name', htmlspecialchars($hopdong['congty']['congty_name']));
                            $templateProcessor->setValue('congty_address', htmlspecialchars($hopdong['congty']['congty_address']));
                            $templateProcessor->setValue('mau_name', htmlspecialchars($hopdong['mau']['mau_name']));
                            $templateProcessor->setValue('mau_description', htmlspecialchars($hopdong['mau']['mau_description']));
                            $templateProcessor->setValue('nenmau_name', htmlspecialchars($hopdong['mau']['nenmau_name']));
                            $templateProcessor->setValue('nhanmau_date', date_format(date_create($hopdong['mau']['date_create']), 'd/m/Y'));
                            $templateProcessor->setValue('result_date', date_format(date_create($hopdong['date_end']), 'd/m/Y'));
                            $templateProcessor->cloneRow('chitieu_name', count($hopdong['list_chitieu']));
                            // Process list chitieu
                            foreach ($hopdong['list_chitieu'] as $index => $chitieu){
                                $templateProcessor->setValue('chitieu_name#'.($index+1), htmlspecialchars($chitieu['chat_name']));
                                $base_rs = $exp_rs = $ketqua_convert = '';
                                if (preg_match($struct_visinh, $chitieu['chat_ketqua'], $match)){
                                    $base_rs = $match[1].' x '.$match[2];
                                    $exp_rs = $match[3];
                                    $ketqua_convert = $match[1] * pow($match[2], $match[3]);
                                }else{
                                    $base_rs = $chitieu['chat_ketqua'];
                                    $exp_rs = '';
                                    $ketqua_convert = $chitieu['chat_ketqua'];
                                }
                                if($chitieu['capacity'] == 1 && is_numeric($ketqua_convert)
                                        && ((isset($chitieu['val_min']) && is_numeric($chitieu['val_min'])) 
                                        || (isset($chitieu['val_max']) && is_numeric($chitieu['val_max'])))){
                                    // So sánh LOD
                                    if($chitieu['lod_loq'] == '1' && $chitieu['val_min']){
                                        if(floatval($ketqua_convert) < floatval($chitieu['val_min'])){
                                            $ketqua_compare = $this->input->post('less_select');
                                            if($ketqua_compare !== FALSE){
                                                $base_rs = $result_compare['less'][$ketqua_compare];
                                                $exp_rs = '';
                                            }
                                        }else{
                                            $ketqua_compare = $this->input->post('more_select');
                                            if($ketqua_compare){
                                                $base_rs = $result_compare['more'][$ketqua_compare];
                                                $exp_rs = '';
                                            }

                                        }
                                    }
                                    // So sánh LOQ
                                    if($chitieu['lod_loq'] == '2' && $chitieu['val_max']){
                                        if(floatval($ketqua_convert) < floatval($chitieu['val_max']) ){
                                            $ketqua_compare = $this->input->post('less_select');
                                            if($ketqua_compare !== FALSE){
                                                $base_rs = $result_compare['less'][$ketqua_compare];
                                                $exp_rs = '';
                                            }
                                        }else{
                                            $ketqua_compare = $this->input->post('more_select');
                                            if($ketqua_compare){
                                                $base_rs = $result_compare['more'][$ketqua_compare];
                                                $exp_rs = '';
                                            }
                                        }
                                    }
                                    // So sánh LOD, LOQ
                                    if($chitieu['lod_loq'] == '3'){
                                        if($chitieu['val_min'] && floatval($ketqua_convert) < floatval($chitieu['val_min'])){
                                            $ketqua_compare = $this->input->post('less_select');
                                            if($ketqua_compare !== FALSE){
                                                $base_rs = $result_compare['less'][$ketqua_compare];
                                                $exp_rs = '';
                                            }
                                        }elseif($chitieu['val_max'] && floatval($ketqua_convert) >= floatval($chitieu['val_max'])){
                                            $ketqua_compare = $this->input->post('more_select');
                                            if($ketqua_compare){
                                                $base_rs = $result_compare['more'][$ketqua_compare];
                                                $exp_rs = '';
                                            }
                                        }elseif(
                                                $chitieu['val_min'] && $chitieu['val_max'] && 
                                                floatval($ketqua_convert) >= floatval($chitieu['val_min'])  && 
                                                floatval($ketqua_convert) < floatval($chitieu['val_max'])
                                        ){
                                            $ketqua_compare = $this->input->post('between_select');
                                            if($ketqua_compare){
                                                if($ketqua_compare == 'compare_loq'){
                                                    $base_rs = '< '.$chitieu['val_max'];
                                                    $exp_rs = '';
                                                }else{
                                                    $base_rs = $result_compare['between'][$ketqua_compare];
                                                    $exp_rs = '';
                                                }
                                            }
                                        }
                                    }
                                }
                                $templateProcessor->setValue('base_rs#'.($index+1), htmlspecialchars($base_rs));
                                $templateProcessor->setValue('exp_rs#'.($index+1), htmlspecialchars($exp_rs));
                                if($chitieu['capacity'] == 1){
                                    switch ($chitieu['lod_loq']){
                                        case '1':
                                            $templateProcessor->setValue('lod#'.($index+1), htmlspecialchars($chitieu['val_min']));
                                            $templateProcessor->setValue('loq#'.($index+1), '');
                                            break;
                                        case '2':
                                            $templateProcessor->setValue('lod#'.($index+1), '');
                                            $templateProcessor->setValue('loq#'.($index+1), htmlspecialchars($chitieu['val_max']));
                                            break;
                                        default :
                                            $templateProcessor->setValue('lod#'.($index+1), htmlspecialchars($chitieu['val_min']));
                                            $templateProcessor->setValue('loq#'.($index+1), htmlspecialchars($chitieu['val_max']));
                                            break;
                                    }
                                }else{
                                    // Can not view MIN-MAX
                                    $templateProcessor->setValue('lod#'.($index+1), '');
                                    $templateProcessor->setValue('loq#'.($index+1), '');
                                }
                                $templateProcessor->setValue('donvitinh#'.($index+1), htmlspecialchars($chitieu['donvitinh']));
                                $phuongphap = $chitieu['phuongphap'];
                                if($chitieu['congnhan']){
                                    foreach ($chitieu['congnhan'] as $congnhan){
                                        $phuongphap .= ' ('.$congnhan['congnhan_sign'].')';
                                    }
                                }
                                $templateProcessor->setValue('phuongphap_name#'.($index+1), htmlspecialchars($phuongphap));
                            }
                            break;
                        case '2':
                            // Create file result
                            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($this->upload_path.'result_template/BM.15.05b.docx');
                            $templateProcessor->setValue('hopdong_code', htmlspecialchars($hopdong['hopdong_code']));
                            $templateProcessor->setValue('date_print', date_format(date_create($date_export), 'd/m/Y'));
                            $templateProcessor->setValue('congty_name', htmlspecialchars($hopdong['congty']['congty_name']));
                            $templateProcessor->setValue('congty_address', htmlspecialchars($hopdong['congty']['congty_address']));
                            $templateProcessor->setValue('chitieu_name', htmlspecialchars($hopdong['chitieu']['chitieu_name']));
                            $templateProcessor->setValue('phuongphap_name', htmlspecialchars($this->input->post('phuongphap_name')));
                            $templateProcessor->setValue('nenmau_name', htmlspecialchars($this->input->post('nenmau_name')));
                            $templateProcessor->setValue('mau_description', htmlspecialchars($this->input->post('mau_description')));
                            $templateProcessor->setValue('nhanmau_date', date_format(date_create($hopdong['hopdong_createdate']), 'd/m/Y'));
                            $templateProcessor->setValue('result_date', date_format(date_create($hopdong['date_end']), 'd/m/Y'));
                            $templateProcessor->setValue('mau_quantity', count($hopdong['list_chitieu']));
                            $templateProcessor->cloneRow('mau_id', count($hopdong['list_chitieu']));
                            // Process list chitieu
                            foreach ($hopdong['list_chitieu'] as $index => $chitieu){
                                $templateProcessor->setValue('mau_id#'.($index+1), htmlspecialchars($chitieu['mau_code']));
                                $templateProcessor->setValue('mau_name#'.($index+1), htmlspecialchars($chitieu['mau_name']));
                                $base_rs = $exp_rs = $ketqua_convert = '';
                                if (preg_match($struct_visinh, $chitieu['ketqua'], $match)){
                                    $base_rs = $match[1].' x '.$match[2];
                                    $exp_rs = $match[3];
                                    $ketqua_convert = $match[1] * pow($match[2], $match[3]);
                                }else{
                                    $base_rs = $chitieu['ketqua'];
                                    $exp_rs = '';
                                    $ketqua_convert = $chitieu['ketqua'];
                                }
                                if($chitieu['capacity'] == 1 && is_numeric($ketqua_convert)
                                        && ((isset($chitieu['val_min']) && is_numeric($chitieu['val_min'])) 
                                        || (isset($chitieu['val_max']) && is_numeric($chitieu['val_max'])))){
                                    // So sánh LOD
                                    if($chitieu['lod_loq'] == '1' && $chitieu['val_min']){
                                        if(floatval($ketqua_convert) < floatval($chitieu['val_min'])){
                                            $ketqua_compare = $this->input->post('less_select');
                                            if($ketqua_compare !== FALSE){
                                                $base_rs = $result_compare['less'][$ketqua_compare];
                                                $exp_rs = '';
                                            }
                                        }else{
                                            $ketqua_compare = $this->input->post('more_select');
                                            if($ketqua_compare){
                                                $base_rs = $result_compare['more'][$ketqua_compare];
                                                $exp_rs = '';
                                            }

                                        }
                                    }
                                    // So sánh LOQ
                                    if($chitieu['lod_loq'] == '2' && $chitieu['val_max']){
                                        if(floatval($ketqua_convert) < floatval($chitieu['val_max']) ){
                                            $ketqua_compare = $this->input->post('less_select');
                                            if($ketqua_compare !== FALSE){
                                                $base_rs = $result_compare['less'][$ketqua_compare];
                                                $exp_rs = '';
                                            }
                                        }else{
                                            $ketqua_compare = $this->input->post('more_select');
                                            if($ketqua_compare){
                                                $base_rs = $result_compare['more'][$ketqua_compare];
                                                $exp_rs = '';
                                            }
                                        }
                                    }
                                    // So sánh LOD, LOQ
                                    if($chitieu['lod_loq'] == '3'){
                                        if($chitieu['val_min'] && floatval($ketqua_convert) < floatval($chitieu['val_min'])){
                                            $ketqua_compare = $this->input->post('less_select');
                                            if($ketqua_compare !== FALSE){
                                                $base_rs = $result_compare['less'][$ketqua_compare];
                                                $exp_rs = '';
                                            }
                                        }elseif($chitieu['val_max'] && floatval($ketqua_convert) >= floatval($chitieu['val_max'])){
                                            $ketqua_compare = $this->input->post('more_select');
                                            if($ketqua_compare){
                                                $base_rs = $result_compare['more'][$ketqua_compare];
                                                $exp_rs = '';
                                            }
                                        }elseif(
                                                $chitieu['val_min'] && $chitieu['val_max'] && 
                                                floatval($ketqua_convert) >= floatval($chitieu['val_min'])  && 
                                                floatval($ketqua_convert) < floatval($chitieu['val_max'])
                                        ){
                                            $ketqua_compare = $this->input->post('between_select');
                                            if($ketqua_compare){
                                                if($ketqua_compare == 'compare_loq'){
                                                    $base_rs = '< '.$chitieu['val_max'];
                                                    $exp_rs = '';
                                                }else{
                                                    $base_rs = $result_compare['between'][$ketqua_compare];
                                                    $exp_rs = '';
                                                }
                                            }
                                        }
                                    }
                                }
                                $templateProcessor->setValue('base_rs#'.($index+1), htmlspecialchars($base_rs));
                                $templateProcessor->setValue('exp_rs#'.($index+1), htmlspecialchars($exp_rs));
                                if($chitieu['capacity'] == 1){
                                    switch ($chitieu['lod_loq']){
                                        case '1':
                                            $templateProcessor->setValue('lod#'.($index+1), htmlspecialchars($chitieu['val_min']));
                                            $templateProcessor->setValue('loq#'.($index+1), '');
                                            break;
                                        case '2':
                                            $templateProcessor->setValue('lod#'.($index+1), '');
                                            $templateProcessor->setValue('loq#'.($index+1), htmlspecialchars($chitieu['val_max']));
                                            break;
                                        default :
                                            $templateProcessor->setValue('lod#'.($index+1), htmlspecialchars($chitieu['val_min']));
                                            $templateProcessor->setValue('loq#'.($index+1), htmlspecialchars($chitieu['val_max']));
                                            break;
                                    }
                                }else{
                                    // Can not view MIN-MAX
                                    $templateProcessor->setValue('lod#'.($index+1), '');
                                    $templateProcessor->setValue('loq#'.($index+1), '');
                                }
                                $templateProcessor->setValue('donvitinh#'.($index+1), htmlspecialchars($chitieu['donvitinh']));
                            }
                            break;
                        case '3':
                            // Create file result
                            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($this->upload_path.'result_template/BM.15.05c.docx');
                            $templateProcessor->setValue('mau_code', htmlspecialchars($hopdong['mau']['mau_code']));
                            $templateProcessor->setValue('hopdong_code', htmlspecialchars($hopdong['hopdong_code']));
                            $templateProcessor->setValue('date_print', date_format(date_create($date_export), 'd/m/Y'));
                            $templateProcessor->setValue('congty_name', htmlspecialchars($hopdong['congty']['congty_name']));
                            $templateProcessor->setValue('congty_address', htmlspecialchars($hopdong['congty']['congty_address']));
                            $templateProcessor->setValue('mau_name', htmlspecialchars($hopdong['mau']['mau_name']));
                            $templateProcessor->setValue('mau_description', htmlspecialchars($hopdong['mau']['mau_description']));
                            $templateProcessor->setValue('nenmau_name', htmlspecialchars($hopdong['mau']['nenmau_name']));
                            $templateProcessor->setValue('nhanmau_date', date_format(date_create($hopdong['mau']['date_create']), 'd/m/Y'));
                            $templateProcessor->setValue('result_date', date_format(date_create($hopdong['date_end']), 'd/m/Y'));
                            $templateProcessor->cloneRow('index', count($hopdong['list_chitieu']));
                            // Process list chitieu
                            foreach ($hopdong['list_chitieu'] as $index => $chitieu){
                                $templateProcessor->setValue('index#'.($index+1), htmlspecialchars($index+1));
                                $templateProcessor->setValue('chitieu_name#'.($index+1), htmlspecialchars($chitieu['chat_name']));
                                $base_rs = $exp_rs = $ketqua_convert = '';
                                if (preg_match($struct_visinh, $chitieu['chat_ketqua'], $match)){
                                    $base_rs = $match[1].' x '.$match[2];
                                    $exp_rs = $match[3];
                                    $ketqua_convert = $match[1] * pow($match[2], $match[3]);
                                }else{
                                    $base_rs = $chitieu['chat_ketqua'];
                                    $exp_rs = '';
                                    $ketqua_convert = $chitieu['chat_ketqua'];
                                }
                                if($chitieu['capacity'] == 1 && is_numeric($ketqua_convert)
                                        && ((isset($chitieu['val_min']) && is_numeric($chitieu['val_min'])) 
                                        || (isset($chitieu['val_max']) && is_numeric($chitieu['val_max'])))){
                                    // So sánh LOD
                                    if($chitieu['lod_loq'] == '1' && $chitieu['val_min']){
                                        if(floatval($ketqua_convert) < floatval($chitieu['val_min'])){
                                            $ketqua_compare = $this->input->post('less_select');
                                            if($ketqua_compare !== FALSE){
                                                $base_rs = $result_compare['less'][$ketqua_compare];
                                                $exp_rs = '';
                                            }
                                        }else{
                                            $ketqua_compare = $this->input->post('more_select');
                                            if($ketqua_compare){
                                                $base_rs = $result_compare['more'][$ketqua_compare];
                                                $exp_rs = '';
                                            }

                                        }
                                    }
                                    // So sánh LOQ
                                    if($chitieu['lod_loq'] == '2' && $chitieu['val_max']){
                                        if(floatval($ketqua_convert) < floatval($chitieu['val_max']) ){
                                            $ketqua_compare = $this->input->post('less_select');
                                            if($ketqua_compare !== FALSE){
                                                $base_rs = $result_compare['less'][$ketqua_compare];
                                                $exp_rs = '';
                                            }
                                        }else{
                                            $ketqua_compare = $this->input->post('more_select');
                                            if($ketqua_compare){
                                                $base_rs = $result_compare['more'][$ketqua_compare];
                                                $exp_rs = '';
                                            }
                                        }
                                    }
                                    // So sánh LOD, LOQ
                                    if($chitieu['lod_loq'] == '3'){
                                        if($chitieu['val_min'] && floatval($ketqua_convert) < floatval($chitieu['val_min'])){
                                            $ketqua_compare = $this->input->post('less_select');
                                            if($ketqua_compare !== FALSE){
                                                $base_rs = $result_compare['less'][$ketqua_compare];
                                                $exp_rs = '';
                                            }
                                        }elseif($chitieu['val_max'] && floatval($ketqua_convert) >= floatval($chitieu['val_max'])){
                                            $ketqua_compare = $this->input->post('more_select');
                                            if($ketqua_compare){
                                                $base_rs = $result_compare['more'][$ketqua_compare];
                                                $exp_rs = '';
                                            }
                                        }elseif(
                                                $chitieu['val_min'] && $chitieu['val_max'] && 
                                                floatval($ketqua_convert) >= floatval($chitieu['val_min'])  && 
                                                floatval($ketqua_convert) < floatval($chitieu['val_max'])
                                        ){
                                            $ketqua_compare = $this->input->post('between_select');
                                            if($ketqua_compare){
                                                if($ketqua_compare == 'compare_loq'){
                                                    $base_rs = '< '.$chitieu['val_max'];
                                                    $exp_rs = '';
                                                }else{
                                                    $base_rs = $result_compare['between'][$ketqua_compare];
                                                    $exp_rs = '';
                                                }
                                            }
                                        }
                                    }
                                }
                                $templateProcessor->setValue('base_rs#'.($index+1), htmlspecialchars($base_rs));
                                $templateProcessor->setValue('exp_rs#'.($index+1), htmlspecialchars($exp_rs));
                                if (preg_match($struct_visinh, $chitieu['mrl_max'], $match)){
                                    $templateProcessor->setValue('base_mrl_max#'.($index+1), htmlspecialchars($match[1].' x '.$match[2]));
                                    $templateProcessor->setValue('exp_mrl_max#'.($index+1), htmlspecialchars($match[3]));
                                    $templateProcessor->setValue('result#'.($index+1), htmlspecialchars(floatval($ketqua_convert) > floatval($match[1]*pow($match[2],$match[3]))?'Không đạt':'Đạt'));
                                }else{
                                    if($chitieu['mrl_max'] && is_numeric($chitieu['mrl_max'])){
                                        $templateProcessor->setValue('base_mrl_max#'.($index+1), htmlspecialchars($chitieu['mrl_max']));
                                        $templateProcessor->setValue('exp_mrl_max#'.($index+1), '');
                                        $templateProcessor->setValue('result#'.($index+1), htmlspecialchars(floatval($ketqua_convert) > floatval($chitieu['mrl_max'])?'Không đạt':'Đạt'));
                                    }else{
                                        $templateProcessor->setValue('base_mrl_max#'.($index+1), htmlspecialchars($chitieu['mrl_max']));
                                        $templateProcessor->setValue('exp_mrl_max#'.($index+1), '');
                                        $templateProcessor->setValue('result#'.($index+1), '');
                                    }
                                }
                                if($chitieu['capacity'] == 1){
                                    switch ($chitieu['lod_loq']){
                                        case '1':
                                            $templateProcessor->setValue('lod#'.($index+1), htmlspecialchars($chitieu['val_min']));
                                            $templateProcessor->setValue('loq#'.($index+1), '');
                                            break;
                                        case '2':
                                            $templateProcessor->setValue('lod#'.($index+1), '');
                                            $templateProcessor->setValue('loq#'.($index+1), htmlspecialchars($chitieu['val_max']));
                                            break;
                                        default :
                                            $templateProcessor->setValue('lod#'.($index+1), htmlspecialchars($chitieu['val_min']));
                                            $templateProcessor->setValue('loq#'.($index+1), htmlspecialchars($chitieu['val_max']));
                                            break;
                                    }
                                }else{
                                    // Can not view MIN-MAX
                                    $templateProcessor->setValue('lod#'.($index+1), '');
                                    $templateProcessor->setValue('loq#'.($index+1), '');
                                }
                                $templateProcessor->setValue('donvitinh#'.($index+1), htmlspecialchars($chitieu['donvitinh']));
                                $phuongphap = $chitieu['phuongphap'];
                                if($chitieu['congnhan']){
                                    foreach ($chitieu['congnhan'] as $congnhan){
                                        $phuongphap .= ' ('.$congnhan['congnhan_sign'].')';
                                    }
                                }
                                $templateProcessor->setValue('phuongphap_name#'.($index+1), htmlspecialchars($phuongphap));
                            }
                            break;
                        case '4':
                            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($this->upload_path.'result_template/BM.15.05d.docx');
                            $templateProcessor->setValue('hopdong_code', htmlspecialchars($hopdong['hopdong_code']));
                            $templateProcessor->setValue('date_print', date_format(date_create($date_export), 'd/m/Y'));
                            $templateProcessor->setValue('mau_name', htmlspecialchars($hopdong['mau']['mau_name']));
                            $templateProcessor->setValue('mau_code', htmlspecialchars($hopdong['mau']['mau_code']));
                            $templateProcessor->setValue('mau_description', htmlspecialchars($hopdong['mau']['mau_description']));
                            $templateProcessor->setValue('mau_timesave', max(array_column($hopdong['list_chitieu'], 'timesave'))?max(array_column($hopdong['list_chitieu'], 'timesave')):0);
                            $templateProcessor->setValue('nhanmau_date', date_format(date_create($hopdong['mau']['date_create']), 'd/m/Y'));
                            $templateProcessor->setValue('phongthinghiem_time', date_format(date_create($hopdong['mau']['date_create']), 'd/m/Y').'-'. date('d/m/Y'));
                            $templateProcessor->cloneRow('chitieu_name', count($hopdong['list_chitieu']));
                            // Process list chitieu
                            foreach ($hopdong['list_chitieu'] as $index => $chitieu){
                                $templateProcessor->setValue('chitieu_name#'.($index+1), htmlspecialchars($chitieu['chat_name']));
                                $base_rs = $exp_rs = $ketqua_convert = '';
                                if (preg_match($struct_visinh, $chitieu['chat_ketqua'], $match)){
                                    $base_rs = $match[1].' x '.$match[2];
                                    $exp_rs = $match[3];
                                    $ketqua_convert = $match[1] * pow($match[2], $match[3]);
                                }else{
                                    $base_rs = $chitieu['chat_ketqua'];
                                    $exp_rs = '';
                                    $ketqua_convert = $chitieu['chat_ketqua'];
                                }
                                if($chitieu['capacity'] == 1 && is_numeric($ketqua_convert)
                                        && ((isset($chitieu['val_min']) && is_numeric($chitieu['val_min'])) 
                                        || (isset($chitieu['val_max']) && is_numeric($chitieu['val_max'])))){
                                    // So sánh LOD
                                    if($chitieu['lod_loq'] == '1' && $chitieu['val_min']){
                                        if(floatval($ketqua_convert) < floatval($chitieu['val_min'])){
                                            $ketqua_compare = $this->input->post('less_select');
                                            if($ketqua_compare !== FALSE){
                                                $base_rs = $result_compare['less'][$ketqua_compare];
                                                $exp_rs = '';
                                            }
                                        }else{
                                            $ketqua_compare = $this->input->post('more_select');
                                            if($ketqua_compare){
                                                $base_rs = $result_compare['more'][$ketqua_compare];
                                                $exp_rs = '';
                                            }

                                        }
                                    }
                                    // So sánh LOQ
                                    if($chitieu['lod_loq'] == '2' && $chitieu['val_max']){
                                        if(floatval($ketqua_convert) < floatval($chitieu['val_max']) ){
                                            $ketqua_compare = $this->input->post('less_select');
                                            if($ketqua_compare !== FALSE){
                                                $base_rs = $result_compare['less'][$ketqua_compare];
                                                $exp_rs = '';
                                            }
                                        }else{
                                            $ketqua_compare = $this->input->post('more_select');
                                            if($ketqua_compare){
                                                $base_rs = $result_compare['more'][$ketqua_compare];
                                                $exp_rs = '';
                                            }
                                        }
                                    }
                                    // So sánh LOD, LOQ
                                    if($chitieu['lod_loq'] == '3'){
                                        if($chitieu['val_min'] && floatval($ketqua_convert) < floatval($chitieu['val_min'])){
                                            $ketqua_compare = $this->input->post('less_select');
                                            if($ketqua_compare !== FALSE){
                                                $base_rs = $result_compare['less'][$ketqua_compare];
                                                $exp_rs = '';
                                            }
                                        }elseif($chitieu['val_max'] && floatval($ketqua_convert) >= floatval($chitieu['val_max'])){
                                            $ketqua_compare = $this->input->post('more_select');
                                            if($ketqua_compare){
                                                $base_rs = $result_compare['more'][$ketqua_compare];
                                                $exp_rs = '';
                                            }
                                        }elseif(
                                                $chitieu['val_min'] && $chitieu['val_max'] && 
                                                floatval($ketqua_convert) >= floatval($chitieu['val_min'])  && 
                                                floatval($ketqua_convert) < floatval($chitieu['val_max'])
                                        ){
                                            $ketqua_compare = $this->input->post('between_select');
                                            if($ketqua_compare){
                                                if($ketqua_compare == 'compare_loq'){
                                                    $base_rs = '< '.$chitieu['val_max'];
                                                    $exp_rs = '';
                                                }else{
                                                    $base_rs = $result_compare['between'][$ketqua_compare];
                                                    $exp_rs = '';
                                                }
                                            }
                                        }
                                    }
                                }
                                $templateProcessor->setValue('base_rs#'.($index+1), htmlspecialchars($base_rs));
                                $templateProcessor->setValue('exp_rs#'.($index+1), htmlspecialchars($exp_rs));
                                if($chitieu['mrl_max'] && is_numeric($chitieu['mrl_max'])){
                                    if (preg_match($struct_visinh, $chitieu['mrl_max'], $match)){
                                        $templateProcessor->setValue('base_mrl_max#'.($index+1), htmlspecialchars($match[1].' x '.$match[2]));
                                        $templateProcessor->setValue('exp_mrl_max#'.($index+1), htmlspecialchars($match[3]));
                                    }
                                }
                                $templateProcessor->setValue('base_mrl_max#'.($index+1), htmlspecialchars($chitieu['mrl_max']));
                                $templateProcessor->setValue('exp_mrl_max#'.($index+1), '');
                                $templateProcessor->setValue('donvitinh#'.($index+1), htmlspecialchars($chitieu['donvitinh']));
                                $phuongphap = $chitieu['phuongphap'];
                                if($chitieu['congnhan']){
                                    foreach ($chitieu['congnhan'] as $congnhan){
                                        $phuongphap .= ' ('.$congnhan['congnhan_sign'].')';
                                    }
                                }
                                $templateProcessor->setValue('phuongphap_name#'.($index+1), htmlspecialchars($phuongphap));
                            }
                            // Add chuky kiemnghiemvien
                            if($this->input->post('user_duyet_0')){
                                // Call API
                                $this->curl->create($this->api_nhansu_url.'nhansu_info');
                                $this->curl->post(array(
                                    'nhansu_id' => trim($this->input->post('user_duyet_0'))
                                ));
                                $result_api = json_decode($this->curl->execute(), TRUE);
                                if($result_api && $result_api['err_code'] == 200){
                                    if(file_exists($result_api['nhansu'][0]['nhansu_sign'])){
                                        $templateProcessor->insertImage('signature_0', $result_api['nhansu'][0]['nhansu_sign'], null, '100px');
                                    }
                                }
                                $templateProcessor->setValue('img:signature_0', '');
                            }
                            break;
                        case '6':
                            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($this->upload_path.'result_template/BM.15.05f.docx');
                            $templateProcessor->setValue('hopdong_code', htmlspecialchars($hopdong['hopdong_code']));
                            $templateProcessor->setValue('date_print', date_format(date_create($date_export), 'd/m/Y'));
                            $templateProcessor->setValue('congty_name', htmlspecialchars($hopdong['congty']['congty_name']));
                            $templateProcessor->setValue('congty_address', htmlspecialchars($hopdong['congty']['congty_address']));
                            $templateProcessor->setValue('mau_name', htmlspecialchars($hopdong['mau']['mau_name']));
                            $templateProcessor->setValue('mau_code', htmlspecialchars($hopdong['mau']['mau_code']));
                            $templateProcessor->setValue('mau_description', htmlspecialchars($hopdong['mau']['mau_description']));
                            $templateProcessor->setValue('nenmau_name', htmlspecialchars($hopdong['mau']['nenmau_name']));
                            $templateProcessor->setValue('nhanmau_date', date_format(date_create($hopdong['mau']['date_create']), 'd/m/Y'));
                            $templateProcessor->setValue('result_date', date_format(date_create($hopdong['date_end']), 'd/m/Y'));
                            $templateProcessor->setValue('ngamthoi', $this->input->post('ngamthoi'));
                            $templateProcessor->cloneRow('chitieu_name', count($hopdong['list_chitieu']));
                            // Process list chitieu
                            foreach ($hopdong['list_chitieu'] as $index => $chitieu){
                                $templateProcessor->setValue('chitieu_name#'.($index+1), htmlspecialchars($chitieu['chat_name']));
                                $base_rs = $exp_rs = $ketqua_convert = '';
                                if (preg_match($struct_visinh, $chitieu['chat_ketqua'], $match)){
                                    $base_rs = $match[1].' x '.$match[2];
                                    $exp_rs = $match[3];
                                    $ketqua_convert = $match[1] * pow($match[2], $match[3]);
                                }else{
                                    $base_rs = $chitieu['chat_ketqua'];
                                    $exp_rs = '';
                                    $ketqua_convert = $chitieu['chat_ketqua'];
                                }
                                if($chitieu['capacity'] == 1 && is_numeric($ketqua_convert)
                                        && ((isset($chitieu['val_min']) && is_numeric($chitieu['val_min'])) 
                                        || (isset($chitieu['val_max']) && is_numeric($chitieu['val_max'])))){
                                    // So sánh LOD
                                    if($chitieu['lod_loq'] == '1' && $chitieu['val_min']){
                                        if(floatval($ketqua_convert) < floatval($chitieu['val_min'])){
                                            $ketqua_compare = $this->input->post('less_select');
                                            if($ketqua_compare !== FALSE){
                                                $base_rs = $result_compare['less'][$ketqua_compare];
                                                $exp_rs = '';
                                            }
                                        }else{
                                            $ketqua_compare = $this->input->post('more_select');
                                            if($ketqua_compare){
                                                $base_rs = $result_compare['more'][$ketqua_compare];
                                                $exp_rs = '';
                                            }

                                        }
                                    }
                                    // So sánh LOQ
                                    if($chitieu['lod_loq'] == '2' && $chitieu['val_max']){
                                        if(floatval($ketqua_convert) < floatval($chitieu['val_max']) ){
                                            $ketqua_compare = $this->input->post('less_select');
                                            if($ketqua_compare !== FALSE){
                                                $base_rs = $result_compare['less'][$ketqua_compare];
                                                $exp_rs = '';
                                            }
                                        }else{
                                            $ketqua_compare = $this->input->post('more_select');
                                            if($ketqua_compare){
                                                $base_rs = $result_compare['more'][$ketqua_compare];
                                                $exp_rs = '';
                                            }
                                        }
                                    }
                                    // So sánh LOD, LOQ
                                    if($chitieu['lod_loq'] == '3'){
                                        if($chitieu['val_min'] && floatval($ketqua_convert) < floatval($chitieu['val_min'])){
                                            $ketqua_compare = $this->input->post('less_select');
                                            if($ketqua_compare !== FALSE){
                                                $base_rs = $result_compare['less'][$ketqua_compare];
                                                $exp_rs = '';
                                            }
                                        }elseif($chitieu['val_max'] && floatval($ketqua_convert) >= floatval($chitieu['val_max'])){
                                            $ketqua_compare = $this->input->post('more_select');
                                            if($ketqua_compare){
                                                $base_rs = $result_compare['more'][$ketqua_compare];
                                                $exp_rs = '';
                                            }
                                        }elseif(
                                                $chitieu['val_min'] && $chitieu['val_max'] && 
                                                floatval($ketqua_convert) >= floatval($chitieu['val_min'])  && 
                                                floatval($ketqua_convert) < floatval($chitieu['val_max'])
                                        ){
                                            $ketqua_compare = $this->input->post('between_select');
                                            if($ketqua_compare){
                                                if($ketqua_compare == 'compare_loq'){
                                                    $base_rs = '< '.$chitieu['val_max'];
                                                    $exp_rs = '';
                                                }else{
                                                    $base_rs = $result_compare['between'][$ketqua_compare];
                                                    $exp_rs = '';
                                                }
                                            }
                                        }
                                    }
                                }
                                $templateProcessor->setValue('base_rs#'.($index+1), htmlspecialchars($base_rs));
                                $templateProcessor->setValue('exp_rs#'.($index+1), htmlspecialchars($exp_rs));
                                if (preg_match($struct_visinh, $chitieu['mrl_max'], $match)){
                                    $templateProcessor->setValue('base_mrl_max#'.($index+1), htmlspecialchars($match[1].' x '.$match[2]));
                                    $templateProcessor->setValue('exp_mrl_max#'.($index+1), htmlspecialchars($match[3]));
                                    $templateProcessor->setValue('result#'.($index+1), htmlspecialchars(floatval($ketqua_convert) > floatval($match[1]*pow($match[2],$match[3]))?'Không đạt':'Đạt'));
                                }else{
                                    if($chitieu['mrl_max'] && is_numeric($chitieu['mrl_max'])){
                                        $templateProcessor->setValue('base_mrl_max#'.($index+1), htmlspecialchars($chitieu['mrl_max']));
                                        $templateProcessor->setValue('exp_mrl_max#'.($index+1), '');
                                        $templateProcessor->setValue('result#'.($index+1), htmlspecialchars(floatval($ketqua_convert) > floatval($chitieu['mrl_max'])?'Không đạt':'Đạt'));
                                    }else{
                                        $templateProcessor->setValue('base_mrl_max#'.($index+1), htmlspecialchars($chitieu['mrl_max']));
                                        $templateProcessor->setValue('exp_mrl_max#'.($index+1), '');
                                        $templateProcessor->setValue('result#'.($index+1), '');
                                    }
                                }
                                if($chitieu['capacity'] == 1){
                                    switch ($chitieu['lod_loq']){
                                        case '1':
                                            $templateProcessor->setValue('lod#'.($index+1), htmlspecialchars($chitieu['val_min']));
                                            $templateProcessor->setValue('loq#'.($index+1), '');
                                            break;
                                        case '2':
                                            $templateProcessor->setValue('lod#'.($index+1), '');
                                            $templateProcessor->setValue('loq#'.($index+1), htmlspecialchars($chitieu['val_max']));
                                            break;
                                        default :
                                            $templateProcessor->setValue('lod#'.($index+1), htmlspecialchars($chitieu['val_min']));
                                            $templateProcessor->setValue('loq#'.($index+1), htmlspecialchars($chitieu['val_max']));
                                            break;
                                    }
                                }else{
                                    // Can not view MIN-MAX
                                    $templateProcessor->setValue('lod#'.($index+1), '');
                                    $templateProcessor->setValue('loq#'.($index+1), '');
                                }
                                $templateProcessor->setValue('donvitinh#'.($index+1), htmlspecialchars($chitieu['donvitinh']));
                                $phuongphap = $chitieu['phuongphap'];
                                if($chitieu['congnhan']){
                                    foreach ($chitieu['congnhan'] as $congnhan){
                                        $phuongphap .= ' ('.$congnhan['congnhan_sign'].')';
                                    }
                                }
                                $templateProcessor->setValue('phuongphap_name#'.($index+1), htmlspecialchars($phuongphap));
                            }
                            break;
                        case '7':
                            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($this->upload_path.'result_template/BM.15.05g.docx');
                            $templateProcessor->setValue('hopdong_code', htmlspecialchars($hopdong['hopdong_code']));
                            $templateProcessor->setValue('date_print', date_format(date_create($date_export), 'd/m/Y'));
                            $templateProcessor->setValue('congty_name', htmlspecialchars($hopdong['congty']['congty_name']));
                            $templateProcessor->setValue('congty_address', htmlspecialchars($hopdong['congty']['congty_address']));
                            $templateProcessor->setValue('mau_name', htmlspecialchars($hopdong['mau']['mau_name']));
                            $templateProcessor->setValue('mau_code', htmlspecialchars($hopdong['mau']['mau_code']));
                            $templateProcessor->setValue('mau_description', htmlspecialchars($hopdong['mau']['mau_description']));
                            $templateProcessor->setValue('nenmau_name', htmlspecialchars($hopdong['mau']['nenmau_name']));
                            $templateProcessor->setValue('nhanmau_date', date_format(date_create($hopdong['mau']['date_create']), 'd/m/Y'));
                            $templateProcessor->setValue('result_date', date_format(date_create($hopdong['date_end']), 'd/m/Y'));
                            $templateProcessor->setValue('select_matong', $this->input->post('select_matong'));
                            $templateProcessor->cloneRow('chitieu_name', count($hopdong['list_chitieu']));
                            // Process list chitieu
                            foreach ($hopdong['list_chitieu'] as $index => $chitieu){
                                $templateProcessor->setValue('chitieu_name#'.($index+1), htmlspecialchars($chitieu['chat_name']));
                                $base_rs = $exp_rs = $ketqua_convert = '';
                                if (preg_match($struct_visinh, $chitieu['chat_ketqua'], $match)){
                                    $base_rs = $match[1].' x '.$match[2];
                                    $exp_rs = $match[3];
                                    $ketqua_convert = $match[1] * pow($match[2], $match[3]);
                                }else{
                                    $base_rs = $chitieu['chat_ketqua'];
                                    $exp_rs = '';
                                    $ketqua_convert = $chitieu['chat_ketqua'];
                                }
                                if($chitieu['capacity'] == 1 && is_numeric($ketqua_convert)
                                        && ((isset($chitieu['val_min']) && is_numeric($chitieu['val_min'])) 
                                        || (isset($chitieu['val_max']) && is_numeric($chitieu['val_max'])))){
                                    // So sánh LOD
                                    if($chitieu['lod_loq'] == '1' && $chitieu['val_min']){
                                        if(floatval($ketqua_convert) < floatval($chitieu['val_min'])){
                                            $ketqua_compare = $this->input->post('less_select');
                                            if($ketqua_compare !== FALSE){
                                                $base_rs = $result_compare['less'][$ketqua_compare];
                                                $exp_rs = '';
                                            }
                                        }else{
                                            $ketqua_compare = $this->input->post('more_select');
                                            if($ketqua_compare){
                                                $base_rs = $result_compare['more'][$ketqua_compare];
                                                $exp_rs = '';
                                            }

                                        }
                                    }
                                    // So sánh LOQ
                                    if($chitieu['lod_loq'] == '2' && $chitieu['val_max']){
                                        if(floatval($ketqua_convert) < floatval($chitieu['val_max']) ){
                                            $ketqua_compare = $this->input->post('less_select');
                                            if($ketqua_compare !== FALSE){
                                                $base_rs = $result_compare['less'][$ketqua_compare];
                                                $exp_rs = '';
                                            }
                                        }else{
                                            $ketqua_compare = $this->input->post('more_select');
                                            if($ketqua_compare){
                                                $base_rs = $result_compare['more'][$ketqua_compare];
                                                $exp_rs = '';
                                            }
                                        }
                                    }
                                    // So sánh LOD, LOQ
                                    if($chitieu['lod_loq'] == '3'){
                                        if($chitieu['val_min'] && floatval($ketqua_convert) < floatval($chitieu['val_min'])){
                                            $ketqua_compare = $this->input->post('less_select');
                                            if($ketqua_compare !== FALSE){
                                                $base_rs = $result_compare['less'][$ketqua_compare];
                                                $exp_rs = '';
                                            }
                                        }elseif($chitieu['val_max'] && floatval($ketqua_convert) >= floatval($chitieu['val_max'])){
                                            $ketqua_compare = $this->input->post('more_select');
                                            if($ketqua_compare){
                                                $base_rs = $result_compare['more'][$ketqua_compare];
                                                $exp_rs = '';
                                            }
                                        }elseif(
                                                $chitieu['val_min'] && $chitieu['val_max'] && 
                                                floatval($ketqua_convert) >= floatval($chitieu['val_min'])  && 
                                                floatval($ketqua_convert) < floatval($chitieu['val_max'])
                                        ){
                                            $ketqua_compare = $this->input->post('between_select');
                                            if($ketqua_compare){
                                                if($ketqua_compare == 'compare_loq'){
                                                    $base_rs = '< '.$chitieu['val_max'];
                                                    $exp_rs = '';
                                                }else{
                                                    $base_rs = $result_compare['between'][$ketqua_compare];
                                                    $exp_rs = '';
                                                }
                                            }
                                        }
                                    }
                                }
                                $templateProcessor->setValue('base_rs#'.($index+1), htmlspecialchars($base_rs));
                                $templateProcessor->setValue('exp_rs#'.($index+1), htmlspecialchars($exp_rs));
                                $templateProcessor->setValue('donvitinh#'.($index+1), htmlspecialchars($chitieu['donvitinh']));
                                $phuongphap = $chitieu['phuongphap'];
                                if($chitieu['congnhan']){
                                    foreach ($chitieu['congnhan'] as $congnhan){
                                        $phuongphap .= ' ('.$congnhan['congnhan_sign'].')';
                                    }
                                }
                                $templateProcessor->setValue('phuongphap_name#'.($index+1), htmlspecialchars($phuongphap));
                            }
                            break;
                    }
                    $templateProcessor->setValue('luuy', htmlspecialchars(trim($this->input->post('ketqua_note'))));
                    // Process congnhan
                    $result_file = $this->upload_path.'result/'.$hopdong['hopdong_code'].'-'.$ketqua_id.'-tmp.docx';
                    $templateProcessor->cloneBlock('BLOCK_GHICHU', count($hopdong['list_congnhan']));
                    $number = 1;
                    $list_image = '';
                    if($hopdong['list_congnhan']){
                        foreach ($hopdong['list_congnhan'] as $congnhan_info){
                            $list_image .= '${img:image'.$number.'} ';
                            $templateProcessor->setValue('ghichu#'.$number, htmlspecialchars('('.$congnhan_info['congnhan_sign'].') '.$congnhan_info['congnhan_name']));
                            $number = $number + 1;
                        }
                    }else{
                        $result_file = $this->upload_path.'result/'.$hopdong['hopdong_code'].'-'.$ketqua_id.'.docx';
                    }
                    // Add image
                    $templateProcessor->setValue('list_image', htmlspecialchars($list_image));
                    $templateProcessor->saveAs($result_file);
                    if($hopdong['list_congnhan']){
                        $docFound = file_exists($result_file);
                        if($docFound){
                            $templateProcessorNew = new \PhpOffice\PhpWord\TemplateProcessor($result_file);
                            $number = 1;
                            foreach ($hopdong['list_congnhan'] as $congnhan_info){
                                if($congnhan_info['file_name'] && file_exists($this->upload_path.'congnhan/'.$congnhan_info['file_name'])){
                                    $templateProcessorNew->insertImage('image'.$number, $this->upload_path.'congnhan/'.$congnhan_info['file_name'], null, '50px');
                                }
                                /*
                                // Call API get file congnhan logo
                                $this->curl->create($this->api_general_url.'get_file');
                                $this->curl->post(array(
                                    'file_id' => trim($congnhan_info['congnhan_logo'])
                                ));
                                $result = json_decode($this->curl->execute(), TRUE);
                                if($result && $result['err_code'] == 200){
                                    if(file_exists($result['site_url'].$result['file'][0]['file_path'])){
                                        $templateProcessorNew->insertImage('image'.$number, $result['site_url'].$result['file'][0]['file_path'], null, '50px');
                                    }
                                }
                                 * 
                                 */
                                $templateProcessorNew->setValue('img:image'.$number, '');
                                $number = $number + 1;
                            }
                            $templateProcessorNew->saveAs($this->upload_path.'result/'.$hopdong['hopdong_code'].'-'.$ketqua_id.'.docx');
                            if(file_exists($this->upload_path.'result/'.$hopdong['hopdong_code'].'-'.$ketqua_id.'.docx')){
                                @shell_exec("sh /selinux/doc2pdf.sh ".$this->upload_path."result/ ".$this->upload_path."result/".$hopdong['hopdong_code']."-".$ketqua_id.".docx"." 2>&1");
                            }
                            unlink($result_file);
                        }
                    }elseif(file_exists($result_file)){
                        @shell_exec("sh /selinux/doc2pdf.sh ".$this->upload_path."result/ ".$result_file." 2>&1");
                    }
                    // Create file remove signature
                    if(file_exists($this->upload_path.'result/'.$hopdong['hopdong_code'].'-'.$ketqua_id.'.docx')){
                        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($this->upload_path.'result/'.$hopdong['hopdong_code'].'-'.$ketqua_id.'.docx');
                        $templateProcessor->setValue('img:signature_1', '');
                        $templateProcessor->setValue('img:signature_2', '');
                        $templateProcessor->setValue('img:signature_3', '');
                        $templateProcessor->saveAs($this->upload_path.'result/'.$hopdong['hopdong_code'].'-'.$ketqua_id.'-download.docx');
                        if(file_exists($this->upload_path.'result/'.$hopdong['hopdong_code'].'-'.$ketqua_id.'-download.docx')){
                            @shell_exec("sh /selinux/doc2pdf.sh ".$this->upload_path."result/ ".$this->upload_path."result/".$hopdong['hopdong_code']."-".$ketqua_id."-download.docx"." 2>&1");
                        }
                    }
                    // Refresh page
                    $this->session->set_userdata('active_result', 1);
                    redirect(site_url().'ketqua/detail?ketqua='.$ketqua_id);
                }
            }
        }
        $data = array(
            'error' => $error,
            'hopdong' => $hopdong,
            'user_duyet' => $user_duyet,
            'date_export' => $date_export,
            'result_compare' => $result_compare,
            'result_template' => $result_template
        );
        if($result_template == '7'){
            $data['matong_list'] = $matong_list;
        }
        $this->parser->assign('data', $data);
        switch ($result_template){
            case '1':
            case '3':
            case '4':
            case '6':
            case '7':
                $this->parser->parse('ketqua/create');
                break;
            case '2':
                $this->parser->parse('ketqua/create_2');
                break;
        }
    }
    function duyetketqua(){
        // Get type
        $approved = $this->input->get('approved');
        $approved = $approved?$approved:0;
        $data = array(
            'approved' => $approved
        );
        $this->parser->assign('data', $data);
        $this->parser->parse('ketqua/approve');
    }
    function detail(){
        $ketqua_id = $this->input->get('ketqua');
        $ketqua = $this->ketqua->get_ketqua_id($ketqua_id);
        $hopdong = $ketqua_other_list = false;
        if($ketqua){
            // Get hopdong info
            $hopdong_info = $this->ketqua->get_hopdong_ketqua($ketqua_id);
            // Get ketqua
            $ketqua_chitiet = $this->ketqua->get_ketqua_chitiet($ketqua_id);
            // Get hopdong chitieu
            $hopdong = $this->get_hopdong($hopdong_info[0]['hopdong_id'], array_column($ketqua_chitiet, 'mauchitiet_id'));
            // Get ketqua other
            $ketqua_other_list = $this->ketqua->get_ketqua_hopdong($hopdong_info[0]['hopdong_id']);
            if($ketqua_other_list){
                foreach ($ketqua_other_list as $key=>$ketqua_other){
                    if($ketqua_other['ketqua_id'] == $ketqua_id){
                        unset($ketqua_other_list[$key]);
                    }else{
                        // Call API get user create ketqua
                        $this->curl->create($this->api_nhansu_url.'nhansu_info');
                        $this->curl->post(array(
                            'nhansu_id' => trim($ketqua_other['user_id'])
                        ));
                        $result = json_decode($this->curl->execute(), TRUE);
                        if($result['err_code'] == 200){
                            $ketqua_other_list[$key]['user_name'] = $result['nhansu'][0]['nhansu_lastname'].' '.$result['nhansu'][0]['nhansu_firstname'];
                        }else{
                            $ketqua_other_list[$key]['user_name'] = '';
                        }
                    }
                }
            }
        }
         // Get list ketqua duyet not approved
        $view_approve = false;
        $duyet_wating = $this->ketqua->get_ketqua_duyet_wating($ketqua_id);
        if($duyet_wating && $duyet_wating['user_receive'] == $this->user_login){
            $view_approve = true;
        }
        // Get list duyet ketqua
        $duyet_latest = false;
        $count_duyet = 0;
        $list_ketqua_duyet = $this->ketqua->get_ketqua_duyet($ketqua_id);
        $list_ketqua_duyet_approved = array();
        if($list_ketqua_duyet){
            foreach ($list_ketqua_duyet as &$ketqua_duyet){
                // Call API get user create ketqua
                $this->curl->create($this->api_nhansu_url.'nhansu_info');
                $this->curl->post(array(
                    'nhansu_id' => trim($ketqua_duyet['user_receive'])
                ));
                $result = json_decode($this->curl->execute(), TRUE);
                if($result['err_code'] == 200){
                    $ketqua_duyet['user_receive_name'] = $result['nhansu'][0]['nhansu_lastname'].' '.$result['nhansu'][0]['nhansu_firstname'];
                }else{
                    $ketqua_duyet['user_receive_name'] = '';
                }
                // Check approved
                if($ketqua_duyet['duyet_result'] != 0){
                    $list_ketqua_duyet_approved[$ketqua_duyet['user_receive']] = $ketqua_duyet;
                }
            }
            $count_duyet = count($list_ketqua_duyet_approved);
            $duyet_latest = end(array_values($list_ketqua_duyet_approved));
        }
        // Get file result latest
        if($duyet_latest){
            $file_result = $this->upload_url.'result/'.$hopdong['hopdong_code'].'-'.$ketqua_id.'-'.$duyet_latest['duyet_id'].'.pdf';
            $file_result_doc = $this->upload_url.'result/'.$hopdong['hopdong_code'].'-'.$ketqua_id.'-'.$duyet_latest['duyet_id'].'.docx';
        }else{
            $file_result = $this->upload_url.'result/'.$hopdong['hopdong_code'].'-'.$ketqua_id.'.pdf';
            $file_result_doc = $this->upload_url.'result/'.$hopdong['hopdong_code'].'-'.$ketqua_id.'.docx';
        }
        $file_download_word = $this->upload_url.'result/'.$hopdong['hopdong_code'].'-'.$ketqua_id.'-download.docx';
        $file_download_pdf = $this->upload_url.'result/'.$hopdong['hopdong_code'].'-'.$ketqua_id.'-download.pdf';
        // Call API get list user duyet
        $user_duyet = false;
        $this->curl->create($this->api_nhansu_url.'duyetketqua');
        $this->curl->post(array(
            'nhansu_id' => trim($ketqua['user_id'])
        ));
        $result = json_decode($this->curl->execute(), TRUE);
        //$list_last_duyet = false;
        if($result['err_code'] == 200){
            if(isset($duyet_latest) && $duyet_latest && $duyet_latest['duyet_result'] == 2){
                foreach ($result['list'] as $user){
                    if($user[0]['id'] == $duyet_latest['user_receive']){
                        $user_duyet = $user;
                    }
                }
            }elseif(isset(array_values($result['list'])[($count_duyet + 1)])){
                $user_duyet = array_values($result['list'])[($count_duyet + 1)];
            }
            //$list_last_duyet = end($result['list']);
        }
        $first_duyet = array_values($result['list'])[0][0];
        $user_is_first = $this->user_login == $first_duyet['id'];
        // Process approve
        $data_post = $this->input->post();
        if($this->input->post() && $view_approve){
            $approve_result = $this->input->post('approve_result');
            // Update ketqua_duyet
            $update_duyet = $this->ketqua->duyet_ketqua(array(
                'duyet_result' => $approve_result,
                'duyet_note' => $this->input->post('duyet_note'),
                'ketqua_id' => $ketqua_id,
                'user_receive' => $this->user_login,
                'duyet_id' => $duyet_wating['duyet_id']
            ));
            // Check cancel ketqua or last duyet
            if($update_duyet){
                // Insert new ketqua_duyet or update ketqua_approve
                if(!$user_duyet){
                    $this->ketqua->update_approve(array(
                        'duyet_result' => $approve_result,
                        'ketqua_id' => $ketqua_id,
                    ));
                }else{
                    if($approve_result == '2'){
                        if($user_is_first){
                            // Thay đổi thông tin hopdong
                            if(isset($data_post['phanhoi_type_1']) && $data_post['phanhoi_type_1'] === '1'){
                                // Insert suco
                                $this->suco->insert_suco(array(
                                    'suco_type' => 1,
                                    'suco_content' => $data_post['approve_content_1'],
                                    'hopdong_id' => $hopdong['hopdong_id'],
                                    'nhansu_id' => $this->user_login,
                                    'suco_createdate' => date("Y-m-d H:i:s")                        
                                ));
                            }
                            // Thay đổi thông tin chitieu
                            if(isset($data_post['phanhoi_type_2']) && $data_post['phanhoi_type_2'] === '2'){
                                foreach ($data_post['chitieu_ketqua'] as $mauketqua_id){
                                    $insert_mauketqua_duyet = $this->ketqua->insert_mauketqua_duyet(array(
                                        'mauketqua_id' => $mauketqua_id,
                                        'user_create' => $this->user_login,
                                        'date_create' => date("Y-m-d H:i:s"),
                                        'mauketqua_note' => $data_post['approve_content_2'],
                                    ));
                                    if($insert_mauketqua_duyet){
                                        $this->ketqua->update_mauketqua_approve(array(
                                            'mauketqua_duyet_id' => $insert_mauketqua_duyet,
                                            'mauketqua_id' => $mauketqua_id
                                        ));
                                    }
                                }
                            }
                            // Update approve_ketqua
                            $this->ketqua->update_approve(array(
                                'duyet_result' => $approve_result,
                                'ketqua_id' => $ketqua_id
                            ));
                        }else{
                            $ketqua_duyet = $this->ketqua->insert_ketqua_duyet(array(
                                'ketqua_id' => $ketqua_id,
                                'user_send' => $this->user_login,
                                'user_receive' => $duyet_wating['user_send'],
                                'parent_duyet_id' => $duyet_wating['duyet_id']
                            ));
                        }
                    }else{
                        $user_duyet_id = $this->input->post('user_duyet');
                        $ketqua_duyet = $this->ketqua->insert_ketqua_duyet(array(
                            'ketqua_id' => $ketqua_id,
                            'user_send' => $this->user_login,
                            'user_receive' => $user_duyet_id,
                            'parent_duyet_id' => $duyet_wating['duyet_id']
                        ));
                    }
                }
                // Check approve ketqua_duyet
                if($view_approve){
                    // Create new file result
                    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($file_result_doc);
                    if($approve_result != '2'){
                        // Call API get file signature user
                        $this->curl->create($this->api_nhansu_url.'nhansu_info');
                        $this->curl->post(array(
                                'nhansu_id' => $this->user_login
                        ));
                        $result_api = json_decode($this->curl->execute(), TRUE);
                        if($result_api && $result_api['err_code'] == 200){
                            if(file_exists($result_api['nhansu'][0]['nhansu_sign'])){
                                $templateProcessor->insertImage('img:signature_'.($count_duyet + 1), $result_api['nhansu'][0]['nhansu_sign'], null, '100px');
                            }
                        }
                        $templateProcessor->setValue('img:signature_'.($count_duyet + 1), '');
                    }
                    $templateProcessor->saveAs($this->upload_path.'result/'.$hopdong['hopdong_code'].'-'.$ketqua_id.'-'.$duyet_wating['duyet_id'].'.docx');
                    if(file_exists($this->upload_path.'result/'.$hopdong['hopdong_code'].'-'.$ketqua_id.'-'.$duyet_wating['duyet_id'].'.docx')){
                        @shell_exec("sh /selinux/doc2pdf.sh ".$this->upload_path."result/ ".$this->upload_path."result/".$hopdong['hopdong_code']."-".$ketqua_id."-".$duyet_wating['duyet_id'].".docx"." 2>&1");
                    }
                }
                // Refresh page
                $this->session->set_userdata('active_result', 2);
                redirect(site_url().'ketqua/detail?ketqua='.$ketqua_id);
            }
        }
        $active_result = $this->session->userdata('active_result');
        $this->session->unset_userdata('active_result');
        $data = array(
            'ketqua' => $ketqua,
            'hopdong' => $hopdong,
            'ketqua_other_list' => $ketqua_other_list,
            'file_result' => $file_result,
            'file_download_word' => $file_download_word,
            'file_download_pdf' => $file_download_pdf,
            'view_approve' => $view_approve,
            'list_ketqua_duyet' => $list_ketqua_duyet,
            'user_duyet' => $user_duyet,
            'duyet_latest' => $duyet_latest,
            'active_result' => $active_result
        );
        $this->parser->assign('data', $data);
        $this->parser->parse('ketqua/detail');
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
        // Check permission
        $permission = $this->permarr;
        // Count list all ketqua
        $count_all = $this->ketqua->count_all($permission[_TKQ_DANHSACHKQ]['master']?FALSE:$this->user_login);
        // Count list search ketqua
        $count_list = $this->ketqua->count_list($permission[_TKQ_DANHSACHKQ]['master']?FALSE:$this->user_login, $search);
        // Get list certificate
        $list_ketqua = $this->ketqua->get_list($permission[_TKQ_DANHSACHKQ]['master']?FALSE:$this->user_login, $search, $sort_column, $sort_direction, $start, $length);
        // Build data        
        $data_ketqua = array();
        if($list_ketqua){
            foreach ($list_ketqua as $ketqua){
                $data_tmp = $ketqua;
                $data_tmp['index'] = ++$start;
                // Add link hopdong detail
                $data_tmp['link_hopdong'] = site_url().'nhanmau/detail?hopdong='.$ketqua['hopdong_code'];
                // Add link to record
                $data_tmp['link_detail'] = site_url().'ketqua/detail?ketqua='.$ketqua['ketqua_id'];
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
    function ajax_list_approve(){
        $data = $this->input->get();
        // Get type
        $approved = $this->input->get('approved')?true:false;
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
        $count_all = $this->ketqua->count_all_approve($this->user_login, $approved);
        // Count list search ketqua
        $count_list = $this->ketqua->count_list_approve($this->user_login, $approved, $search);
        // Get list certificate
        $list_ketqua = $this->ketqua->get_list_approve($this->user_login, $approved, $search, $sort_column, $sort_direction, $start, $length);
        // Build data        
        $data_ketqua = array();
        if($list_ketqua){
            foreach ($list_ketqua as $ketqua){
                $data_tmp = $ketqua;
                $data_tmp['index'] = ++$start;
                // Add link to record
                $data_tmp['link_detail'] = site_url().'ketqua/detail?ketqua='.$ketqua['ketqua_id'];
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
                // Get result approve
                $ketqua_duyet = $this->ketqua->get_ketqua_duyet_info($ketqua['duyet_id']);
                if($ketqua_duyet){
                    $data_tmp['duyet_result'] = $ketqua_duyet['duyet_result'];
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
    function ajax_list_hopdong(){
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
        // Check permission
        $permission = $this->permarr;
        if(!$permission || !isset($permission[_TKQ_PHIEUKQ]) || 
            (!$permission[_TKQ_PHIEUKQ]['write'] && !$permission[_TKQ_PHIEUKQ]['master'])){
            echo json_encode(array('error' => 'Không có quyền truy cập'), JSON_UNESCAPED_UNICODE);
            exit;
        }
        // Count list all hopdong
        $count_all_hopdong = $this->hopdong->count_all_hopdong();
        // Count list search hopdong
        $count_list_hopdong = $this->hopdong->count_list_hopdong($search);
        // Get list certificate
        $list_hopdong = $this->hopdong->get_list_hopdong($search, $sort_column, $sort_direction, $start, $length);
        // Build data        
        $data_hopdong = array();
        foreach ($list_hopdong as $hopdong){
            $hopdong['action'] = '';
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
            // Count mau in hopdong
            $count_mau = $this->hopdong->count_mau_hopdong($hopdong['hopdong_id']);
            $data_tmp['total_mau'] = $count_mau?$count_mau['total_mau']:0;
            // Count chitieu has result
            $count_chitieu_has_result = $this->hopdong->count_chitieu_result($hopdong['hopdong_id'], TRUE);
            $data_tmp['chitieu_has_result'] = $count_chitieu_has_result?$count_chitieu_has_result['total_chitieu']:0;
            $count_chitieu_hopdong = $this->hopdong->count_chitieu_hopdong($hopdong['hopdong_id'], FALSE);
            $data_tmp['total_chitieu'] = $count_chitieu_hopdong?$count_chitieu_hopdong['total_chitieu']:0;
            $count_chitieu_export = $this->hopdong->count_chitieu_export($hopdong['hopdong_id']);
            $data_tmp['total_chitieu_export'] = $count_chitieu_export?$count_chitieu_export['total_chitieu']:0;
            // Add link to record
            $data_tmp['link_detail'] = site_url().'ketqua/hopdong/detail?hopdong='.$hopdong['hopdong_code'];
            $data_hopdong[] = $data_tmp;
        }
        $result['draw'] = $draw;
        $result['recordsTotal'] = $count_all_hopdong;
        $result['recordsFiltered'] = $count_list_hopdong;
        $result['data'] = $data_hopdong;
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit;
    }
    private function get_hopdong($hopdong_id, $list_mauchitiet_id){
        $hopdong = $this->hopdong->get_hopdong_id($hopdong_id);
        if($hopdong && count($list_mauchitiet_id) > 0){
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
            $list_mau = $this->hopdong->get_mau_list_chitieu($list_mauchitiet_id);
            //var_dump($list_mau);
            if($list_mau){
                foreach ($list_mau as &$mau){
                    // Get chi tieu
                    $list_chitieu = $this->hopdong->get_chitieu_mau($mau['mau_id']);
                    $list_chitieu_info = array();
                    if($list_chitieu){
                        foreach ($list_chitieu as $chitieu_info){
                            if(in_array($chitieu_info['mauchitiet_id'], $list_mauchitiet_id)){
                                $chitieu_info['list_ketqua'] = json_decode($chitieu_info['list_ketqua'], TRUE);
                                $chitieu_info['list_chat_info'] = $this->phongthinghiem->get_list_chat_info(json_decode($chitieu_info['list_chat'], TRUE));
                                $list_chitieu_info[] = $chitieu_info;
                            }
                        }
                    }
                    $mau['list_chitieu_info'] = $list_chitieu_info;
                }
                $hopdong['list_mau'] = $list_mau;
            }
        }
        /*
        echo '<pre>';
        var_dump($hopdong);
        echo '</pre>';*/
        return $hopdong;
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
                $hopdong['mau'] = $list_mau[0];
            }
            // Get list chitieu
            $list_chitieu_info = $this->hopdong->get_chitieu_list_id($list_chitieu);
            if($list_chitieu_info){
                $date_end = '';
                $chitieu_export = array();
                $list_congnhan = array();
                $list_mauketqua_approve = array();
                foreach ($list_chitieu_info as $key=>$chitieu_info){
                    // Rewrite phuongphap name
                    $phuongphap = '';
                    if($chitieu_info['phuongphap_loai'] === '2'){
                        $phuongphap = $chitieu_info['phuongphap_shortname'];
                    }else{
                        if($chitieu_info['phuongphap_ref']){
                            $phuongphap_ref = $this->nenmau->getPhuongphapInfo($chitieu_info['phuongphap_ref']);
                            if($phuongphap_ref){
                                $phuongphap = $chitieu_info['phuongphap_code'].' '.$phuongphap_ref['phuongphap_shortname'];
                            }
                        }
                        if(!$phuongphap){
                            $phuongphap = $chitieu_info['phuongphap_code'];
                        }
                    }
                    // Get mauketqua_duyet
                    /*
                    if($chitieu_info['mauketqua_duyet_id']){
                        $mau_ketqua_duyet = $this->hopdong->get_mau_ketqua_duyet($chitieu_info['mauketqua_duyet_id']);
                        if($mau_ketqua_duyet){
                            $list_chitieu_info[$key]['mauketqua_approve'] = $mau_ketqua_duyet['mauketqua_approve'];
                            $list_chitieu_info[$key]['user_approve'] = $mau_ketqua_duyet['user_approve'];
                        }
                    }*/
                    // Get dateend max
                    if(!$date_end || $date_end < $chitieu_info['chitieu_dateend']){
                        $date_end = $chitieu_info['chitieu_dateend'];
                    }
                    // Get user approve
                    if($chitieu_info['user_approve']){
                        // Call API
                        $this->curl->create($this->api_nhansu_url.'nhansu_info');
                        $this->curl->post(array(
                            'nhansu_id' => $chitieu_info['user_approve']
                        ));
                        $result_api = json_decode($this->curl->execute(), TRUE);
                        if($result_api['err_code'] == 200){
                            $list_mauketqua_approve[$chitieu_info['user_approve']] = $result_api['nhansu'][0]['nhansu_lastname'].' '.$result_api['nhansu'][0]['nhansu_firstname'];
                        }
                    }
                    // Get chat info
                    $list_chat = $this->hopdong->get_list_chat_id(json_decode($chitieu_info['list_chat'], TRUE), $chitieu_info['dongia_id'], $hopdong['thitruong_id']);
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
                                'donvitinh' => $chitieu_info['donvitinh_name'],
                                'phuongphap' => $phuongphap,
                                'lod_loq' => $chitieu_info['lod_loq'],
                                'timesave' => $chitieu_info['thoigian'],
                                'chat_name' => $chat['chat_name'],
                                'chat_ketqua' => $list_ketqua[$chat['chat_id']],
                                'capacity' => $chat['capacity'],
                                'val_min' => $chat['val_min'],
                                'val_max' => $chat['val_max'],
                                'mrl_min' => $chat['mrl_min'],
                                'mrl_max' => $chat['mrl_max'],
                                'congnhan' => $list_congnhan_chat
                            );
                        }
                    }
                }
                $hopdong['list_mauketqua_approve'] = $list_mauketqua_approve;
                $hopdong['date_end'] = $date_end;
                $hopdong['list_chitieu'] = $chitieu_export;
                $hopdong['list_chitieu_info'] = $list_chitieu_info;
                $hopdong['list_congnhan'] = $list_congnhan;
            }
        }
        return $hopdong;
    }
    private function get_hopdong_mau($hopdong_id, $list_chitieu){
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
            // Get chitieu info
            $list_chitieu_info = $this->hopdong->get_chitieu_list_chitieu($list_chitieu);
            //var_dump($list_chitieu_info[0]);
            if($list_chitieu_info){
                $hopdong['chitieu'] = $list_chitieu_info[0];
            }
            // Get list chitieu
            $list_chitieu_info = $this->hopdong->get_chitieu_list_id($list_chitieu);
            if($list_chitieu_info){
                $date_end = '';
                $row_export = array();
                $list_congnhan = array();
                foreach ($list_chitieu_info as $key=>$chitieu_info){
                    // Rewrite phuongphap name
                    $phuongphap = '';
                    if($chitieu_info['phuongphap_loai'] === '2'){
                        $phuongphap = $chitieu_info['phuongphap_shortname'];
                    }else{
                        if($chitieu_info['phuongphap_ref']){
                            $phuongphap_ref = $this->nenmau->getPhuongphapInfo($chitieu_info['phuongphap_ref']);
                            if($phuongphap_ref){
                                $phuongphap = $chitieu_info['phuongphap_code'].' '.$phuongphap_ref['phuongphap_shortname'];
                            }
                        }
                        if(!$phuongphap){
                            $phuongphap = $chitieu_info['phuongphap_code'];
                        }
                    }
                    // Get mauketqua_duyet
                    /*
                    if($chitieu_info['mauketqua_duyet_id']){
                        $mau_ketqua_duyet = $this->hopdong->get_mau_ketqua_duyet($chitieu_info['mauketqua_duyet_id']);
                        if($mau_ketqua_duyet){
                            $list_chitieu_info[$key]['mauketqua_approve'] = $mau_ketqua_duyet['mauketqua_approve'];
                            $list_chitieu_info[$key]['user_approve'] = $mau_ketqua_duyet['user_approve'];
                        }
                    }*/
                    // Get dateend max
                    if(!$date_end || $date_end < $chitieu_info['chitieu_dateend']){
                        $date_end = $chitieu_info['chitieu_dateend'];
                    }
                    // Get chat info
                    $list_chat = $this->hopdong->get_list_chat_id(json_decode($chitieu_info['list_chat'], TRUE)[0], $chitieu_info['dongia_id'], $hopdong['thitruong_id']);
                    $list_ketqua = json_decode($chitieu_info['list_ketqua'], TRUE);
                    if($list_chat){
                        // Get congnhan chat
                        $list_congnhan_chat = $this->nenmau->get_congnhan_chat($list_chat[0]['chat_id']);
                        if($list_congnhan_chat){
                            foreach ($list_congnhan_chat as $congnhan_chat){
                                $list_congnhan[$congnhan_chat['congnhan_id']] = $congnhan_chat;
                            }
                        }
                        $row_export[] = array(
                            'mau_code' => $chitieu_info['mau_code'],
                            'mau_name' => $chitieu_info['mau_name'],
                            'mau_description' => $chitieu_info['mau_description'],
                            'nenmau_name' => $chitieu_info['nenmau_name'],
                            'phuongphap' => $phuongphap,
                            'donvitinh' => $chitieu_info['donvitinh_name'],
                            'lod_loq' => $chitieu_info['lod_loq'],
                            'ketqua' => $list_ketqua[$list_chat[0]['chat_id']],
                            'capacity' => $list_chat[0]['capacity'],
                            'val_min' => $list_chat[0]['val_min'],
                            'val_max' => $list_chat[0]['val_max'],
                            'mrl_min' => $list_chat[0]['mrl_min'],
                            'mrl_max' => $list_chat[0]['mrl_max'],
                            'congnhan' => $list_congnhan_chat
                        );
                        
                    }
                }
                $hopdong['date_end'] = $date_end;
                $hopdong['list_chitieu'] = $row_export;
                $hopdong['list_chitieu_info'] = $list_chitieu_info;
                $hopdong['list_congnhan'] = $list_congnhan;
            }
        }
        return $hopdong;
    }
}