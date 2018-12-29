<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nhanmau extends ADMIN_Controller {
    private $api_khachhang_url = 'http://test.cenlab.vn/khachhang/api/';
    private $api_nhansu_url = 'http://test.cenlab.vn/nhansu/api/';
    private $hopdong_language = array(
        '0' => 'Tiếng Việt',
        '1' => 'Tiếng Anh',
        '2' => 'Anh-Việt'
    );
    private $hopdong_approve = array(
        '0' => 'Chưa duyệt',
        '1' => 'Đồng ý',
        '2' => 'Hủy hợp đồng',
        '3' => 'Sửa hợp đồng',
        '4' => 'PTN duyệt'
    );
    private $user_login = false;
    private $upload_path;
    private $upload_url;
    function __construct() {
        parent::__construct();
        $this->load->library('curl');
        $this->load->model('mod_nenmau', 'nenmau');
        $this->load->model('mod_hopdong', 'hopdong');
        $this->load->model('mod_suco', 'suco');
        // Set user login
        if(ENVIRONMENT === 'local'){
            $this->user_login = 2;
            define('_NM_DUYETHOPDONG', 17);
            define('_NM_PHIEUYEUCAU', 16);
            $this->permarr = array(
                16 => array('write' => 'checked', 'update' => 'checked'),
                17 => array('write' => 'checked')
            );
            $this->upload_path = '_uploads/';
            $this->upload_url = base_url().'_uploads/';
            /*
            $this->permarr = array(
                16 => array('write' => 'checked', 'update' => 'checked')
                //16 => array('read' => 'checked')
            );*/
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
        $data = array();
        // Check permission
        $permission = $this->permarr;
        if(!$permission || !isset($permission[_NM_PHIEUYEUCAU]) || 
                (!$permission[_NM_PHIEUYEUCAU]['read'] && !$permission[_NM_PHIEUYEUCAU]['write'] && !$permission[_NM_PHIEUYEUCAU]['master'])){
            $data['error'] = 'Không có quyền truy cập, vui lòng liên hệ Admin để được cấp quyền.';
        }
        // Get type
        $hopdong_mau = $this->input->get('hopdong_mau')?$this->input->get('hopdong_mau'):0;
        $data['hopdong_mau'] = $hopdong_mau;
        $this->parser->assign('data', $data);
        if($hopdong_mau == '0'){
            $this->parser->parse('nhanmau/hopdong');
        }else{
            $this->parser->parse('nhanmau/hopdong_mau');
        }
    }
    function duyet(){
        // Get type
        $approved = $this->input->get('approved')?$this->input->get('approved'):0;
        $data = array(
            'approved' => $approved
        );
        // Check permission
        $permission = $this->permarr;
        if($permission && isset($permission[_NM_DUYETHOPDONG]) && ($permission[_NM_DUYETHOPDONG]['write'] || $permission[_NM_DUYETHOPDONG]['master'])){
            $data['get_all'] = true;
        }else{
            $data['error'] = 'Không có quyền duyệt Hợp đồng, Vui lòng liên hệ Admin để được cấp quyền.';
        }
        $this->parser->assign('data', $data);
        $this->parser->parse('nhanmau/duyet');
    }
    function add(){
        $hopdong_code = $this->input->get('hopdong');
        $hopdong_copy = trim($this->input->get('hopdong_copy'))?TRUE:FALSE;
        // Check permission
        $permission = $this->permarr;
        if(!$permission || !isset($permission[_NM_PHIEUYEUCAU]) || (!$permission[_NM_PHIEUYEUCAU]['write'] && !$permission[_NM_PHIEUYEUCAU]['master'])){
            redirect(site_url().'admin/denied?w=write');
            exit;
        }
        if($hopdong_code && !$hopdong_copy && (!$permission || !isset($permission[_NM_PHIEUYEUCAU]) || (!$permission[_NM_PHIEUYEUCAU]['update'] && !$permission[_NM_PHIEUYEUCAU]['master']))){
            redirect(site_url().'admin/denied?w=update');
            exit;
        }
        // Get all nenmau
        $list_dvt = $this->nenmau->getDvtNhanmau();
        $list_nenmau = $this->nenmau->getAllNenmau();
        $list_thitruong = $this->nenmau->getAllThitruong();
        $list_dichvu = $this->nenmau->getAllDichvu();
        $hopdong = $hopdong_code?$this->get_hopdong($hopdong_code):false;
        $hopdong_mau = (trim($this->input->get('hopdong_mau')) || $hopdong['hopdong_status'] == '3') && !$hopdong_copy ? TRUE : FALSE;
        $data = array(
            'nhansu_id' => $this->user_login,
            'list_dvt' => $list_dvt,
            'list_nenmau' => $list_nenmau,
            'list_thitruong' => $list_thitruong,
            'list_dichvu' => $list_dichvu,
            'hopdong' => $hopdong?$hopdong:array('list_mau' => array('list_chitieu' => false)),
            'hopdong_mau' => $hopdong_mau,
            'add' => $this->input->get('add')
        );
        if($this->input->post()){
            $error = array();
            // Process congty
            $congty_id = $this->input->post('congty_id');
            // If congty not exist then add congty
            if(!$congty_id){
                $congty_name = $this->input->post('congty_name');
                if(!$congty_name){
                    $error['congty_name'] = 'Tên công ty không để trống';
                }
                if(count($error) == 0){
                    $this->curl->create($this->api_khachhang_url.'congty_add');
                    $this->curl->post(array(
                        'name' => $congty_name,
                        'address' => trim($this->input->post('congty_address')),
                        'phone' => trim($this->input->post('congty_phone')),
                        'fax' => trim($this->input->post('congty_fax')),
                        'email' => trim($this->input->post('congty_email')),
                        'tax' => trim($this->input->post('congty_tax'))
                    ));
                    $result = json_decode($this->curl->execute(), TRUE);
                    if($result['err_code'] == 200){
                        $congty_id = $result['congty']['congty_id'];
                    }else{
                        $error['general'] = 'Không thêm được dữ liệu công ty';
                    }
                }
            }
            // Call API get info congty
            $this->curl->create($this->api_khachhang_url.'list_congty');
            $this->curl->post(array(
                'congty_id' => trim($congty_id)
            ));
            $result = json_decode($this->curl->execute(), TRUE);
            $congty_info = $result['err_code'] == 200?$result['list_congty'][0]:false;
            
            $contact_id = $this->input->post('contact_id');
            // If contact not exist then add contact
            if(!$contact_id){
                if(count($error) == 0){
                    $contact_name = $this->input->post('contact_fullname');
                    if(!$contact_name){
                        $error['contact_fullname'] = 'Tên liên hệ không để trống';
                    }
                    if(count($error) == 0){
                        $array_name = explode(' ', $contact_name);
                        $ten = trim($array_name[count($array_name) - 1]);
                        unset($array_name[count($array_name) - 1]);
                        $ho = trim(count($array_name)>0?implode(' ', $array_name):'');
                        $contact_birthday = trim($this->input->post('contact_birthday'));
                        
                        $this->curl->create($this->api_khachhang_url.'contact_add');
                        $this->curl->post(array(
                            'ho' => $ho,
                            'ten' => $ten,
                            'email' => trim($this->input->post('contact_email')),
                            'phone' => trim($this->input->post('contact_phone')),
                            'ngaysinh' => $contact_birthday?DateTime::createFromFormat('d/m/Y', $contact_birthday)->format('d-m-Y'):null,
                            'congty_id' => $congty_id
                        ));
                        $result = json_decode($this->curl->execute(), TRUE);
                        if($result['err_code'] == 200){
                            $contact_id = $result['contact']['contact_id'];
                        }else{
                            $error['general'] = 'Không thêm được dữ liệu người liên hệ';
                        }
                    }
                }
            }
            // Call API get contact info
            $this->curl->create($this->api_khachhang_url.'list_contact');
            $this->curl->post(array(
                'contact_id' => trim($contact_id)
            ));
            $result = json_decode($this->curl->execute(), TRUE);
            $contact_info = $result['err_code'] == 200?$result['list_contact'][0]:false;
            
            // Connect congty and contact
            if($congty_id && $contact_id){
                if(count($error) == 0){
                    $this->curl->create($this->api_khachhang_url.'add_congty_contact');
                    $this->curl->post(array(
                        'congty_id' => $congty_id,
                        'contact_id' => $contact_id
                    ));
                    $result = json_decode($this->curl->execute(), TRUE);
                    if($result['err_code'] != 200){
                        $error['general'] = 'Không thêm được dữ liệu người liên hệ vào công ty';
                    }
                }
            }
            // Get info hopdong
            $hopdong_thitruong = $this->input->post('hopdong_thitruong');
            $hopdong_resultlang = $this->input->post('hopdong_resultlang');
            $hopdong_resultvia = $this->input->post('hopdong_resultvia');
            $hopdong_quychuan = $this->input->post('hopdong_quychuan');
            $hopdong_yeucaukhac = $this->input->post('hopdong_yeucaukhac');
            $hopdong_dateend = $this->input->post('hopdong_dateend');
            // Create hopdong_code
            if($hopdong_mau){
                $hopdong_code = $this->input->post('hopdong_code');
            }else{
                $hopdong_code = $hopdong && !$hopdong_copy ? $hopdong['hopdong_code'] : false;
                if(!$hopdong_code){
                    $count_hopdong_day = $this->hopdong->countHopdongInDay();
                    $number_hopdong_day = $count_hopdong_day?$count_hopdong_day['hopdong_in_day']:0;
                    $hopdong_code = date("ymd").sprintf('%03d', $number_hopdong_day + 1);
                }
            }
            // Check hopdong_code
            if($hopdong_code){
                $hopdong_mau_check = $this->hopdong->get_hopdong_code($hopdong_code, $hopdong?$hopdong['hopdong_id']:false);
                if($hopdong_mau_check){
                    $error['general'] = 'Mã hợp đồng đã tồn tại';
                }
            }else{
                $error['general'] = 'Mã hợp đồng không được để trống';
            }
            // Add hopdong
            if(count($error) == 0){
                // Create hopdong
                $hopdong_id = $this->hopdong->insertHopdong(array(
                    'hopdong_idparent' => $hopdong && !$hopdong_copy ? $hopdong['hopdong_id'] : 0,
                    'hopdong_code' => $hopdong_code,
                    'congty_id' => $congty_id,
                    'contact_id' => $contact_id,
                    'nhansu_id' => $this->user_login,
                    'thitruong_id' => $hopdong_thitruong,
                    'hopdong_resultlang' => $hopdong_resultlang,
                    'hopdong_resultvia' => $hopdong_resultvia,
                    'hopdong_quychuan' => $hopdong_quychuan,
                    'hopdong_yeucaukhac' => $hopdong_yeucaukhac,
                    'hopdong_dateend' => $hopdong_dateend,
                    'hopdong_mau' => $hopdong_mau
                ));
                if(!$hopdong_id){
                    $error['general'] = 'Không lưu được phiếu yêu cầu thử nghiệm';
                }else if($hopdong && !$hopdong_copy){
                    // Disable hopdong old
                    $update_status = $this->hopdong->updateStatusHopdong($hopdong['hopdong_id'], 2);
                    if(!$update_status){
                        $error['general'] = 'Không cập nhật được trạng thái phiếu yêu cầu thử nghiệm';
                    }
                }
            }
            // Process list mau
            $total_price_default = $total_price = $hopdong_remaining = $hopdong_deposit = 0;
            $list_mau = $this->input->post('mau');
            if($list_mau && is_array($list_mau)){
                // Get hopdong original, count mau in hopdong old
                $hopdong_original = false;
                $count_mau_hopdong = false;
                if($hopdong && !$hopdong_copy){
                    $hopdong_original = $this->hopdong->get_hopdong_original($hopdong['hopdong_code']);
                    $count_mau_hopdong = $this->hopdong->countMauInHopDong($hopdong['hopdong_code']);
                }
                // Count hopdong in month
                $count_hopdong_month = $this->hopdong->countHopdongInMonth($hopdong_original?$hopdong_original['hopdong_id']:false);
                $number_hopdong_month = $count_hopdong_month&&$count_hopdong_month['hopdong_in_month']?$count_hopdong_month['hopdong_in_month']:1;
                // Number mau start
                $number_mau_start = $count_mau_hopdong?$count_mau_hopdong['mau_in_hopdong']:0;
                foreach ($list_mau as $key=>$mau){
                    // Create mau_code
                    if($mau['mau_code'] && !$hopdong_copy){
                        $mau_code = $mau['mau_code'];
                    }else{
                        $mau_code = date("ym").sprintf('%03d', $number_hopdong_month).'-'.(++$number_mau_start);
                        $list_mau[$key]['mau_code'] = $mau_code;
                    }
                    // Date create
                    $mau['date_create'] = $mau['date_create']?$mau['date_create']:date("Y-m-d H:i:s");
                    // Validation info mau
                    if(!trim($mau['name']) || !trim($mau['mass']) || !trim($mau['donvitinh']) || !trim($mau['description']) || !trim($mau['amount'])){
                        $error['mau'] = 'Thông tin mẫu không đầy đủ';
                    }
                    // Get nenmau info
                    $nenmau_info = $this->nenmau->getNenmauById($mau['nenmau']);
                    if($nenmau_info){
                        $list_mau[$key]['nenmau_name'] = $nenmau_info['nenmau_name'];
                    }else{
                        $error['mau'] = 'Nền mẫu không tồn tại';
                    }
                    // Add mau
                    if(count($error) == 0){
                        $mau_id = $this->hopdong->insertMau(array(
                            'mau_code' => $mau_code,
                            'mau_name' => $mau['name'],
                            'mau_mass' => $mau['mass'],
                            'donvitinh_id' => $mau['donvitinh'],
                            'mau_description' => $mau['description'],
                            'mau_amount' => $mau['amount'],
                            'mau_save' => $mau['mau_save'],
                            'mau_note' => $mau['mau_note'],
                            'mau_datesave' => $mau['mau_datesave'],
                            'mau_datesave_yeucau' => $mau['mau_datesave_yeucau'],
                            'hopdong_id' => $hopdong_id,
                            'nenmau_id' => $mau['nenmau'],
                            'date_create' => $mau['date_create']
                        ));
                        if(!$mau_id){
                            $error['mau'] = 'Không lưu được thông tin mẫu';
                        }elseif($mau['mau_id']){
                            $lits_mauptn = $this->hopdong->get_mauptn($mau['mau_id']);
                            if($lits_mauptn){
                                foreach ($lits_mauptn as $mauptn){
                                    $this->hopdong->insert_mauptn(array(
                                        'mau_id' => $mau_id,
                                        'donvi_id' => $mauptn['donvi_id'],
                                        'nhansu_id' => $mauptn['nhansu_id'],
                                        'mauptn_approve' => $mauptn['mauptn_approve'],
                                        'mauptn_note' => $mauptn['mauptn_note'],
                                        'mauptn_createdate' => $mauptn['mauptn_createdate'],
                                        'mauptn_status' => $mauptn['mauptn_status']
                                    ));
                                }
                            }
                        }
                    }
                    // Process package
                    $list_chitieu = $mau['chitieu'];
                    if($list_chitieu && is_array($list_chitieu)){
                        foreach ($list_chitieu as $key_chitieu=>$chitieu){
                            // Get info package
                            $package = $this->nenmau->getPackage(
                                    $mau['nenmau'], $chitieu['chitieu'], 
                                    $chitieu['phuongphap'], $chitieu['kythuat'], 
                                    $chitieu['ptn']
                            );
                            if($package){
                                // Calculator price
                                $total_price_default += $chitieu['price_default'];
                                $total_price += $chitieu['price'];
                                $list_mau[$key]['chitieu'][$key_chitieu]['package'] = $package;
                                // Process mau_chitiet
                                $mau_chitiet_info = false;
                                if($chitieu['mauchitiet_id']){
                                    $mau_chitiet_info = $this->get_mau_chitiet($chitieu['mauchitiet_id']);
                                    $mau_chitiet_info['mau_id'] = $mau_id;
                                    $mau_chitiet_info['dichvu_id'] = $chitieu['dichvu'];
                                    $mau_chitiet_info['price'] = $chitieu['price'];
                                }
                                if(!$mau_chitiet_info){
                                    // Create mau_chitiet
                                    $mau_chitiet_info = array(
                                        'mau_id' => $mau_id,
                                        'chitieu_id' => $chitieu['chitieu'],
                                        'phuongphap_id' => $chitieu['phuongphap'],
                                        'kythuat_id' => $chitieu['kythuat'],
                                        'donvi_id' => $chitieu['ptn'],
                                        'list_chat' => json_encode(explode(',', $chitieu['chat'])),
                                        'lod_loq' => $chitieu['lod_loq'],
                                        'chitieu_dateend' => $chitieu['chitieu_dateend'],
                                        'dichvu_id' => $chitieu['dichvu'],
                                        'price_tmp' => $chitieu['price_default'],
                                        'price' => $chitieu['price']
                                    );
                                }
                                if($mau_chitiet_info && count($error) == 0){
                                    $insert_mau_chitiet = $this->hopdong->insertMauChitiet($mau_chitiet_info);
                                    if(!$insert_mau_chitiet){
                                        $error['mau'] = 'Không lưu được thông tin chỉ tiêu thí nghiệm';
                                    }else{
                                        if($mau_chitiet_info['list_mau_ketqua'] && is_array($mau_chitiet_info['list_mau_ketqua'])){
                                            //TODO: Check chỉ tiêu đã xuất kết quả chưa
                                            foreach ($mau_chitiet_info['list_mau_ketqua'] as $mau_ketqua){
                                                $insert_mau_ketqua = $this->hopdong->insert_mauketqua(array(
                                                    'mauchitiet_id' => $insert_mau_chitiet,
                                                    'list_ketqua' => $mau_ketqua['list_ketqua'],
                                                    'user_create' => $mau_ketqua['user_create'],
                                                    'date_create' => $mau_ketqua['date_create'],
                                                    'mauketqua_ghichu' => $mau_ketqua['mauketqua_ghichu'],
                                                    'mauketqua_current' => $mau_ketqua['mauketqua_current']
                                                ));
                                                if(!$insert_mau_ketqua){
                                                    $error['mau'] = 'Không copy được mau_ketqua';
                                                }else{
                                                    if($mau_ketqua['list_ketqua_duyet'] && is_array($mau_ketqua['list_ketqua_duyet'])){
                                                        foreach ($mau_ketqua['list_ketqua_duyet'] as $ketqua_duyet){
                                                            $insert_ketqua_duyet = $this->hopdong->insert_mauketqua_duyet(array(
                                                                'mauketqua_id' => $insert_mau_ketqua,
                                                                'user_create' => $ketqua_duyet['user_create'],
                                                                'date_create' => $ketqua_duyet['date_create'],
                                                                'user_approve' => $ketqua_duyet['user_approve'],
                                                                'date_approve' => $ketqua_duyet['date_approve'],
                                                                'mauketqua_approve' => $ketqua_duyet['mauketqua_approve'],
                                                                'mauketqua_note' => $ketqua_duyet['mauketqua_note']
                                                            ));
                                                            if(!$insert_ketqua_duyet){
                                                                $error['mau'] = 'Không copy được mau_ketqua_duyet';
                                                            }else{
                                                                $this->hopdong->update_mauketqua_approve(array(
                                                                    'mauketqua_duyet_id' => $insert_ketqua_duyet,
                                                                    'mauketqua_id' => $insert_mau_ketqua
                                                                ));
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }else{
                                $error['mau'] = 'Thông tin chỉ tiêu thí nghiệm không đúng';
                            }
                        }
                    }else{
                        $error['mau'] = 'Không có chỉ tiêu thí nghiệm';
                    }
                }
                // Update price hopdong
                $hopdong_deposit = $this->input->post('hopdong_deposit');
                $hopdong_remaining = $total_price - $hopdong_deposit;
                $this->hopdong->updatePriceHopdong($hopdong_id, $total_price_default, $total_price, $hopdong_deposit, $hopdong_remaining);
            }else{
                $error['mau'] = 'Không có mẫu nào';
            }
            
            // Insert file
            $list_file = $this->input->post('hopdong_file');
            if($list_file && is_array($list_file)){
                $hopdong_files = array();
                foreach ($list_file as $file){
                    $hopdong_files[] = array(
                        'hopdong_id' => $hopdong_id,
                        'file_id' => $file
                    );
                }
                if(count($error) == 0 && count($hopdong_files) > 0){
                    $insert_hopdong_file = $this->hopdong->insertHopdongFile($hopdong_files);
                    if(!$insert_hopdong_file){
                        $error['mau'] = 'Không lưu được thông tin file mẫu';
                    }
                }
            }
            
            $data['error'] = $error;
            $data['post'] = $this->input->post();
            // Create hopdong word/pdf
            if(count($error) == 0){
                $hopdong_doc_tmp = $this->upload_path.'hopdong/'.$hopdong_code.'_tmp.docx';
                $hopdong_doc = $this->upload_path.'hopdong/'.$hopdong_code.'.docx';
                $hopdong_pdf = $this->upload_path.'hopdong/'.$hopdong_code.'.pdf';
                if(file_exists($hopdong_doc_tmp)){ unlink($hopdong_doc_tmp);}
                if(file_exists($hopdong_doc)){ unlink($hopdong_doc);}
                if(file_exists($hopdong_pdf)){ unlink($hopdong_pdf);}
                if(file_exists($this->upload_path.'hopdong_template.docx')){
                    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($this->upload_path.'hopdong_template.docx');
                    $templateProcessor->setValue('hopdong_code', $hopdong_code);
                    $templateProcessor->setValue('date_print', date("d/m/Y H:i:s"));
                    $templateProcessor->setValue('date_result', $hopdong_dateend);
                    $templateProcessor->setValue('hopdong_language', $this->hopdong_language[$hopdong_resultlang]);
                    foreach ($list_thitruong as $thitruong){
                        if($thitruong['thitruong_id'] == $hopdong_thitruong){
                            $templateProcessor->setValue('hopdong_thitruong', $thitruong['thitruong_name']);
                        }
                    }
                    $templateProcessor->setValue('hopdong_thitruong', '');
                    $templateProcessor->setValue('hopdong_price', $total_price);
                    $templateProcessor->setValue('hopdong_deposit', $hopdong_deposit);
                    $templateProcessor->setValue('hopdong_remaining', $hopdong_remaining);
                    if($congty_info){
                        $templateProcessor->setValue('congty_name', $congty_info['congty_name']);
                        $templateProcessor->setValue('congty_address', $congty_info['congty_address']);
                        $templateProcessor->setValue('congty_email', $congty_info['congty_email']);
                        $templateProcessor->setValue('congty_phone', $congty_info['congty_phone']);
                        $templateProcessor->setValue('congty_tax', $congty_info['congty_tax']);
                    }
                    if($contact_info){
                        $templateProcessor->setValue('contact_name', $contact_info['contact_fullname']);
                        $templateProcessor->setValue('contact_email', $contact_info['contact_email']);
                        $templateProcessor->setValue('contact_phone', $contact_info['contact_phone']);
                    }
                    if($list_mau && is_array($list_mau)){
                        $templateProcessor->cloneBlock('DS_MAU', count($list_mau));
                        $index_mau = 1;
                        foreach ($list_mau as $mau){
                            $templateProcessor->setValue('nenmau_name#'.$index_mau, $mau['nenmau_name']);
                            $templateProcessor->setValue('mau_name#'.$index_mau, $mau['name']);
                            $templateProcessor->setValue('mau_mass#'.$index_mau, $mau['mass']);
                            foreach ($list_dvt as $dvt){
                                if($dvt['donvitinh_id'] == $mau['donvitinh']){
                                    $templateProcessor->setValue('mau_dvt#'.$index_mau, $dvt['donvitinh_name']);
                                }
                            }
                            $templateProcessor->setValue('mau_dvt#'.$index_mau, '');
                            $templateProcessor->setValue('mau_amount#'.$index_mau, $mau['amount']);
                            $templateProcessor->setValue('mau_description#'.$index_mau, $mau['description']);
                            $list_chitieu = $mau['chitieu'];
                            if($list_chitieu && is_array($list_chitieu)){
                                $templateProcessor->cloneRow('chitieu_name#'.$index_mau, count($list_chitieu));
                                $index_chitieu = 1;
                                foreach ($list_chitieu as $chitieu){
                                    $templateProcessor->setValue('chitieu_name#'.$index_mau.'#'.$index_chitieu, $chitieu['package']['chitieu_name']);
                                    $templateProcessor->setValue('phuongphap_name#'.$index_mau.'#'.$index_chitieu, $chitieu['package']['phuongphap_name']);
                                    $templateProcessor->setValue('chitieu_date_result#'.$index_mau.'#'.$index_chitieu, $chitieu['chitieu_dateend']);
                                    $templateProcessor->setValue('chitieu_price#'.$index_mau.'#'.$index_chitieu, $chitieu['price']);
                                    $list_chat = explode(',', $chitieu['chat']);
                                    $list_chat_info = $this->nenmau->get_chat_by_id_list($list_chat);
                                    if($list_chat_info){
                                        $list_chat_name = array();
                                        foreach ($list_chat_info as $chat){
                                            $list_chat_name[] = $chat['chat_name'];
                                        }
                                        $templateProcessor->setValue('list_chat#'.$index_mau.'#'.$index_chitieu, implode(', ', $list_chat_name));
                                    }
                                    $index_chitieu++;
                                }
                            }
                            $index_mau++;
                        }
                    }
                    // Add @image text
                    $list_image = '';
                    $list_file = $this->hopdong->get_file_hopdong($hopdong_id);
                    if($list_file && is_array($list_file)){
                        foreach ($list_file as $key=>$file){
                            $list_image .= '${img:mau_image#'.($key+1).'}  ';
                            $file['file_url'] = $this->upload_url.'hopdong/thumbnail/'.$file['nhansu_id'].'_'.$file['file_id'].'.'.$file['file_exts'];
                        }
                    }
                    $templateProcessor->setValue('mau_image', $list_image);
                    $templateProcessor->saveAs($hopdong_doc_tmp);
                    if(file_exists($hopdong_doc_tmp)){
                        $templateProcessorNew = new \PhpOffice\PhpWord\TemplateProcessor($hopdong_doc_tmp);
                        if($list_file && is_array($list_file)){
                            foreach ($list_file as $key=>$file){
                                $maufile_path = $this->upload_path.'hopdong/'.$this->user_login.'_'.$file['file_id'].'.'.$file['file_exts'];
                                if(file_exists($maufile_path)){
                                    $templateProcessorNew->insertImage('mau_image#'.($key+1), $maufile_path, null, '150px');
                                }else{
                                    $templateProcessorNew->setValue('img:mau_image#'.($key+1), '');
                                }
                            }
                        }
                        $templateProcessorNew->saveAs($hopdong_doc);
                        if(file_exists($hopdong_doc)){
                            @shell_exec("sh /selinux/doc2pdf.sh ".$this->upload_path.'hopdong/'.' '.$hopdong_doc." 2>&1");
                        }
                        unlink($hopdong_doc_tmp);
                    }
                }
            }
            // If all suceess redirect new page
            if(count($error) == 0){
                if($hopdong && !$hopdong_copy){
                    redirect(site_url().'nhanmau/detail?hopdong='.$hopdong['hopdong_code'].'&edit=true');
                }else{
                    redirect(site_url().'nhanmau/add?add=true');
                }
            }
        }
        $this->parser->assign('data', $data);
        $this->parser->parse('nhanmau/add');
    }
    function detail(){
        $hopdong_code = $this->input->get('hopdong');
        $hopdong_id = $this->input->get('hopdong_id');
        // Check permission
        $duyet_hopdong = false;
        $permission = $this->permarr;
        if($permission && isset($permission[_NM_DUYETHOPDONG]) && ($permission[_NM_DUYETHOPDONG]['write'] || $permission[_NM_DUYETHOPDONG]['master'])){
            $duyet_hopdong = true;
        }
        $hopdong = $this->get_hopdong($hopdong_code, $hopdong_id);
        $data = array(
            'hopdong' => $hopdong,
            'edit' => $this->input->get('edit'),
            'approve' => $this->input->get('approve'),
            'duyet_hopdong' => $duyet_hopdong
        );
        $this->parser->assign('data', $data);
        $this->parser->parse('nhanmau/detail');
    }
    function mau_print(){
        $hopdong_id = $this->input->get('hopdong_id');
        $hopdong = $this->get_hopdong(false, $hopdong_id);
        $data = array(
            'hopdong' => $hopdong
        );
        $this->parser->assign('data', $data);
        $this->parser->parse('nhanmau/mau_print');
    }
    function edit_request(){
        
    }
    private function get_hopdong($hopdong_code, $hopdong_id = false){
        if($hopdong_id){
            $hopdong = $this->hopdong->get_hopdong_info($hopdong_id);
        }else{
            $hopdong = $this->hopdong->get_hopdong_code($hopdong_code);
        }
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
                $hopdong['nhansu'] = $result['nhansu'][0];
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
                            // Check mau in ptn
                            if($chitieu['mauptn_approve'] !== NULL && !$hopdong_id){
                                $hopdong['mau_in_ptn'] = TRUE;
                            }
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
                            if(isset($mau['nenmau_id']) && isset($chitieu['chitieu_id'])){
                                $time_nenmau_chitieu = $this->nenmau->getTimeSave($mau['nenmau_id'], $chitieu['chitieu_id']);
                                $chitieu['time_save'] = date("d/m/Y", time() + $time_nenmau_chitieu['thoigian']*86400);
                            }
                        }
                    }
                    $mau['list_chitieu'] = $list_chitieu?$list_chitieu:false;
                }
                $hopdong['list_mau'] = $list_mau;
                $hopdong['list_thitruong'] = $list_thitruong;
            }
            // Get file mau of hopdong
            $list_file = $this->hopdong->get_file_hopdong($hopdong['hopdong_id']);
            if($list_file){
                foreach ($list_file as &$file){
                    $file['file_url'] = $this->upload_url.'hopdong/thumbnail/'.$file['nhansu_id'].'_'.$file['file_id'].'.'.$file['file_exts'];
                }
                $hopdong['list_file'] = $list_file;
            }
            // Download hopdong
            $hopdong['file_download_word'] = $this->upload_url.'hopdong/'.$hopdong['hopdong_code'].'.docx';
            $hopdong['file_download_pdf'] = $this->upload_url.'hopdong/'.$hopdong['hopdong_code'].'.pdf';
        }
        return $hopdong;
    }
    private function get_mau_chitiet($mauchitiet_id){
        $mau_chitiet = $this->hopdong->get_mau_chitiet($mauchitiet_id);
        if($mau_chitiet){
            $list_mau_ketqua = $this->hopdong->get_mau_ketqua($mauchitiet_id);
            if($list_mau_ketqua){
                foreach ($list_mau_ketqua as &$mau_ketqua){
                    $mau_ketqua['list_ketqua_duyet'] = $this->hopdong->get_mau_ketqua_duyet($mau_ketqua['mauketqua_id']);
                }
                $mau_chitiet['list_mau_ketqua'] = $list_mau_ketqua;
            }
            if($mau_chitiet){
                $mau_chitiet['chitieu_dateend'] = DateTime::createFromFormat('Y-m-d', trim($mau_chitiet['chitieu_dateend']))->format('d/m/Y');
            }
        }
        return $mau_chitiet;
    }
    function ajax_edit_request(){
        $output = array();
        $hopdong_id = trim($this->input->post('hopdong_id'));
        $edit_id = trim($this->input->post('edit_id'));
        $title = trim($this->input->post('edit_title'));
        $content = trim($this->input->post('edit_content'));
        if(!$hopdong_id || !$title || !$content){
            $output['code'] = 0;
            $output['message'] = 'Tiêu đề và nội dung đề nghị không để trống!';
        }else{
            // Get hopdong info
            $hopdong = $this->hopdong->get_hopdong_id($hopdong_id);
            if($hopdong){
                if($hopdong['suahopdong_approve'] == NULL || $hopdong['suahopdong_approve'] == '2'){
                    // Insert edit request
                    $suahopdong = array(
                        'suahopdong_name' => $title,
                        'suahopdong_content' => $content,
                        'nguoitao_id' => $this->user_login,
                        'hopdong_id' => $hopdong_id,
                        'suahopdong_approve' => 0,
                        'nguoiduyet_id' => null,
                        'nguoisua_id' => null,
                    );
                    $insert_edit_request = $this->hopdong->insertRequestEdit($suahopdong);
                    if($insert_edit_request){
                        $output['code'] = 1;
                        $output['message'] = 'Thêm đề nghị sửa hợp đồng thành công!';
                    }else{
                        $output['code'] = 0;
                        $output['message'] = 'Không thêm được đề nghị sửa hợp đồng!';
                    }
                }elseif($edit_id){
                    // Update edit request
                
                }else{
                    $output['code'] = 0;
                    $output['message'] = 'Đề nghị sửa hợp đồng đang được xử lý!';
                }
            }else{
                $output['code'] = 0;
                $output['message'] = 'Hợp đồng không tồn tại!';
            }
        }
        echo json_encode($output);
    }
    function ajax_approve_history(){
        $output = array();
        $hopdong_id = $this->input->post('hopdong_id');
        $approve_history = $this->hopdong->get_approve_history($hopdong_id);
        if($approve_history){
            foreach ($approve_history as &$history){
                $history['hopdong_approve_txt'] = $this->hopdong_approve[$history['hopdong_approve']];
                // Get info user
                if($history['duyet_user_id']){
                    $this->curl->create($this->api_nhansu_url.'nhansu_info');
                    $this->curl->post(array(
                        'nhansu_id' => $history['duyet_user_id']
                    ));
                    $result_api = json_decode($this->curl->execute(), TRUE);
                    if($result_api['err_code'] == 200){
                        $history['duyet_user'] = $result_api['nhansu'][0]['nhansu_lastname'].' '.$result_api['nhansu'][0]['nhansu_firstname'];
                    }
                }else{
                    $history['duyet_user'] = $history['duyet_user_id'];
                }
            }
            $output['code'] = 1;
            $output['data'] = $approve_history;
        }else{
            $output['code'] = 0;
            $output['message'] = 'Không có lịch sử duyệt';
        }
        echo json_encode($output);
    }
    function ajax_approve(){
        $output = array();
        $hopdong_id = $this->input->post('hopdong_id');
        $hopdong_approve = $this->input->post('hopdong_approve');
        $duyet_content = $this->input->post('duyet_content');
        $hopdong = $this->hopdong->get_hopdong_id($hopdong_id);
        if($hopdong){
            $mau_in_ptn = $this->hopdong->check_mau_in_ptn($hopdong_id);
            if($mau_in_ptn && $mau_in_ptn['total_mau_in_ptn'] > 0){
                switch ($hopdong_approve){
                    case '1':
                        $hopdong_approve = 4;
                        break;
                    case '2':
                        $this->hopdong->disable_hopdong($hopdong_id);
                        $this->hopdong->enable_hopdong($hopdong['hopdong_idparent']);
                        break;
                }
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
                    if($hopdong_approve == 3){
                        // Add new suahopdong
                        $suahopdong = array(
                            'suahopdong_name' => 'Sửa phiếu: '.$hopdong['hopdong_code'],
                            'suahopdong_content' => $duyet_content,
                            'suahopdong_approve' => 1,
                            'nguoitao_id' => $this->user_login,
                            'nguoiduyet_id' => $this->user_login,
                            'nguoisua_id' => $hopdong['nhansu_id'],
                            'hopdong_id' => $hopdong['hopdong_id']
                        );
                        $edit_id = $this->hopdong->insertRequestEdit($suahopdong);
                    }
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
        }
        // Get type for duyet
        $approved = $this->input->get('approved')?true:false;
        // Count list all hopdong
        $count_all_hopdong = $this->hopdong->count_all_hopdong($get_for, $hopdong_mau, $approved);
        // Count list search hopdong
        $count_list_hopdong = $this->hopdong->count_list_hopdong($get_for, $hopdong_mau, $approved, $search);
        // Get list certificate
        $list_hopdong = $this->hopdong->get_list_hopdong($get_for, $hopdong_mau, $approved, $search, $sort_column, $sort_direction, $start, $length);
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
            // Get hopdong suco
            $list_suco = $this->suco->get_suco_hopdong($hopdong['hopdong_id']);
            if($list_suco){
                $data_tmp['total_suco'] = count($list_suco);
            }
            $list_suco_khachhang = $this->suco->get_suco_hopdong_khachhang($hopdong['hopdong_id']);
            if($list_suco_khachhang){
                $data_tmp['total_suco'] = $data_tmp['total_suco'] + count($list_suco_khachhang);
            }
            // Add link detail
            $data_tmp['link_detail'] = site_url().'nhanmau/detail?hopdong='.$hopdong['hopdong_code'];
            if($permission && isset($permission[_NM_PHIEUYEUCAU]) && ($permission[_NM_PHIEUYEUCAU]['write'] || $permission[_NM_PHIEUYEUCAU]['master'])){
                $data_tmp['link_copy'] = site_url().'nhanmau/add?hopdong='.$hopdong['hopdong_code'].'&hopdong_copy=1';
            }
            // Add link edit
            if($hopdong['nhansu_id'] == $this->user_login && 
                    $permission && isset($permission[_NM_PHIEUYEUCAU]) && ($permission[_NM_PHIEUYEUCAU]['update'] || $permission[_NM_PHIEUYEUCAU]['master'])){
                $data_tmp['link_edit'] = site_url().'nhanmau/add?hopdong='.$hopdong['hopdong_code'];
            }
            $mau_in_ptn = $this->hopdong->check_mau_in_ptn($hopdong['hopdong_id']);
            if($mau_in_ptn && $mau_in_ptn['total_mau_in_ptn'] > 0 && $hopdong['hopdong_approve'] == '0'){
                unset($data_tmp['link_edit']);
            }
            $data_hopdong[] = $data_tmp;
        }
        $result['draw'] = $draw;
        $result['recordsTotal'] = $count_all_hopdong;
        $result['recordsFiltered'] = $count_list_hopdong;
        $result['data'] = $data_hopdong;
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit;
    }
    function ajax_load_package(){
        $output = array();
        $congty_id = $this->input->post('congty_id');
        $package = $this->input->post('package');
        if($package){
            // Package construct
            $output['package'] = array(
                'chitieu' => array(array('id' => '', 'name' => 'Chọn chỉ tiêu')),
                'phuongphap' => array(array('id' => '', 'name' => 'Chọn phương pháp')),
                'kythuat' => array(array('id' => '', 'name' => 'Chọn kỹ thuật')),
                'ptn' => array(array('id' => '', 'name' => 'Chọn PTN')),
                'chat' => array(),
                'info' => false
            );
            // Get chitieu by nenmau
            if(isset($package['nenmau_id']) && $package['nenmau_id'] != '' && $package['nenmau_id'] > 0){
                $list_chitieu = $this->nenmau->getChitieuPackage($package['nenmau_id']);
                $output['package']['chitieu'] = is_array($list_chitieu)?array_merge($output['package']['chitieu'], $list_chitieu):$output['package']['chitieu'];
            }
            // Get phuongphap by nenmau, chitieu
            if(isset($package['chitieu_id']) && $package['chitieu_id'] != '' && $package['chitieu_id'] > 0){
                $list_phuongphap = $this->nenmau->getPhuongphapPackage($package['nenmau_id'], $package['chitieu_id']);
                $output['package']['phuongphap'] = is_array($list_phuongphap)?array_merge($output['package']['phuongphap'], $list_phuongphap):$output['package']['phuongphap'];
            }
            // Get kythuat by nenmau, chitieu, phuongphap
            if(isset($package['phuongphap_id']) && $package['phuongphap_id'] != '' && $package['phuongphap_id'] > 0){
                $list_kythuat = $this->nenmau->getKythuatPackage($package['nenmau_id'], $package['chitieu_id'], $package['phuongphap_id']);
                //var_dump($list_kythuat);
                $output['package']['kythuat'] = is_array($list_kythuat)?array_merge($output['package']['kythuat'], $list_kythuat):$output['package']['kythuat'];
            }
            // Get ptn by nenmau, chitieu, phuongphap, kythuat
            if(isset($package['kythuat_id']) && $package['kythuat_id'] != '' && $package['kythuat_id'] > 0){
                $list_ptn = $this->nenmau->getPtnPackage($package['nenmau_id'], $package['chitieu_id'], $package['phuongphap_id'], $package['kythuat_id']);
                if($list_ptn){
                    foreach ($list_ptn as &$ptn){
                        // Call API
                        $this->curl->create($this->api_nhansu_url.'get_donvi');
                        $this->curl->post(array(
                            'donvi_id' => trim($ptn['id'])
                        ));
                        $result = json_decode($this->curl->execute(), TRUE);
                        if($result['err_code'] == 200){
                            $ptn['name'] = $result['donvi_name'];
                        }
                    }
                }
                $output['package']['ptn'] = is_array($list_ptn)?array_merge($output['package']['ptn'], $list_ptn):$output['package']['ptn'];
            }
            // Get chat by chitieu
            /*
            if(isset($package['chitieu_id']) && $package['chitieu_id'] != '' && $package['chitieu_id'] > 0){
                if(isset($package['dongia_id'])){
                    $list_chat = $this->nenmau->getChatPackage($package['dongia_id']);
                }else{
                    // Hiện tại quy định 1 
                    $list_chat = $this->nenmau->getChatChitieu($package['chitieu_id'], $package['nenmau_id']);
                }
                //var_dump($list_chat);
                if($list_chat){
                    foreach ($list_chat as &$chat){
                        $chat['list_thitruong'] = $this->nenmau->getThiTruongChat($chat['id']);
                    }
                    $output['package']['chat'] = array_merge($output['package']['chat'], $list_chat);
                }
            }*/
            // Get time save mau
            if(isset($package['nenmau_id']) && $package['nenmau_id'] != '' && $package['nenmau_id'] > 0 && 
                    isset($package['chitieu_id']) && $package['chitieu_id'] != '' && $package['chitieu_id'] > 0){
                $time_nenmau_chitieu = $this->nenmau->getTimeSave($package['nenmau_id'], $package['chitieu_id']);
                $output['package']['time_save'] = date("d/m/Y", time() + $time_nenmau_chitieu['thoigian']*86400);
            }
            // Get full package info
            $packageInfo = $this->nenmau->getPackage($package['nenmau_id'], $package['chitieu_id'], $package['phuongphap_id'], $package['kythuat_id'], $package['donvi_id']);
            if($packageInfo){
                // Get chitieu in package
                $list_chat = $this->nenmau->getChatPackage($packageInfo['dongia_id']);
                if($list_chat){
                    foreach ($list_chat as &$chat){
                        $chat['list_thitruong'] = $this->nenmau->getThiTruongChat($chat['id']);
                    }
                    $output['package']['chat'] = array_merge($output['package']['chat'], $list_chat);
                }
                // Get gia by khachhang
                if($congty_id){
                    $dongia_khachhang = $this->nenmau->get_dongia_khachhang($packageInfo['dongia_id'], $congty_id);
                    if($dongia_khachhang){
                        $packageInfo['price'] = $dongia_khachhang['price'];
                    }
                }
                // Get giale
                $chatgia_list = $this->nenmau->get_chatgia($packageInfo['dongia_id']);
                if(!$chatgia_list){
                    $chatgia_list = $this->nenmau->get_chatgia(0);
                }
                if($congty_id){
                    $chatgia_khachhang = $this->nenmau->get_chatgia_khachhang($packageInfo['dongia_id'], $congty_id);
                    if($chatgia_khachhang){
                        $chatgia_list = $chatgia_khachhang;
                    }
                }
                if($chatgia_list){
                    $sum_tmp = 0;
                    $lastElementKey = end(array_keys($chatgia_list));
                    foreach ($chatgia_list as $key=>$chatgia){
                        $sum_tmp += $chatgia['gia_price'];
                        if($key != $lastElementKey){
                            $chatgia_list[$key]['gia_price'] = $sum_tmp;
                        }
                    }
                }
                // Set info package
                $packageInfo['chatgia_list'] = $chatgia_list?array_column($chatgia_list, 'gia_price'):'';
                $packageInfo['thoigian'] = date("d/m/Y", time() + $packageInfo['thoigian']*86400);
                $output['package']['info'] = $packageInfo;
            }
            $output['code'] = 1;
        }else{
            $output['code'] = 0;
        }
        echo json_encode($output);
    }
    function ajax_search_package(){
        $output = array();
        $nenmau_id = $this->input->post('nenmau_id');
        $package_code = $this->input->post('package_code');
        $list_package = $this->nenmau->getPackages($package_code, $nenmau_id);
        if($list_package){
            foreach ($list_package as $package){
                $package['list_chat'] = array_column($this->nenmau->getChatByChitieu($package['chitieu_id']),'chat_id');
                $output[] = array(
                    "label" => $package['package_code'],
                    "package" => $package,
                    "category" => ""
                );
            }
        }
        echo json_encode($output);
    }
    function ajax_search_contact(){
        $output = array();
        // Call API
        $this->curl->create($this->api_khachhang_url.'list_contact');
        $this->curl->post(array(
            'congty_id' => trim($this->input->post('congty_id')),
            'key_contact_name' => trim($this->input->post('contact_name'))
        ));
        $result = json_decode($this->curl->execute(), TRUE);
        if($result['err_code'] == 200){
            foreach ($result['list_contact'] as $contact){
                $output[] = array(
                    "label" => $contact['contact_fullname'],
                    "info" => $contact,
                    "category" => ""
                );
            }
        }
        echo json_encode($output);
    }
    function ajax_search_congty(){
        $output = array();
        // Call API
        $this->curl->create($this->api_khachhang_url.'list_congty');
        $this->curl->post(array(
            'key_congty_name' => trim($this->input->post('congty_name'))
        ));
        $result = json_decode($this->curl->execute(), TRUE);
        if($result['err_code'] == 200){
            foreach ($result['list_congty'] as $congty){
                $output[] = array(
                    "label" => $congty['congty_name'],
                    "info" => $congty,
                    "category" => ""
                );
            }
        }
        echo json_encode($output);
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