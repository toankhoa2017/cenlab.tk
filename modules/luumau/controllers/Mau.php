<?php

class Mau extends ADMIN_Controller {

    private $privcheck;

    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('mau');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->privcheck = $this->permarr[_LUU_MAU];
        $this->parser->assign('privcheck', $this->privcheck);
        $this->load->model('mod_mau');
    }

    function index() {
        if (!$this->privcheck['read']) 
            redirect(site_url() . 'admin/denied?w=read');
        $this->parser->parse('mau/list');
    }

    function ajax_list() { 
        $ngonngu = $this->lang->getLang();
        $this->curl->create($this->_api['nhanmau'] . 'listMau');
        $this->curl->post(array(
            'donvi' => $this->session->userdata('ssAdminDonvi'),
            'post' => @$_POST
        ));
        $list = json_decode($this->curl->execute());
        //var_dump($this->session->userdata('ssAdminDonvi'));
        $data = array();
        $today = date("Y-m-d");
        foreach ($list->list as $mau) {
            if($this->mod_mau->checkMauCon($mau->id)){
                $statusMau = $this->mod_mau->getStatusMau($mau->id);
                $row = array();
                $row[] = "<a href='" . site_url() . "luumau/mau/detail/" . $mau->id . "'>" . $mau->code . '</a>';
                $row[] = $mau->name;
                $ngayluu = ($mau->ngayluuyeucau) ? $mau->ngayluuyeucau : $mau->ngayluu;
                $row[] = date("d-m-Y", strtotime($ngayluu));
                $row[] = $mau->dieukienluu;
                if($statusMau > 0){
                    $row[] = '<span class="green">'.$ngonngu['status_daluu'].'</span>';
                }else{
                    $row[] = '<span class="blue">'.$ngonngu['status_chualuu'].'</span>';
                }
                $expire = "";
                if(strtotime($ngayluu) < strtotime($today)){
                    $expire = "color: red;font-weight: bold;"; 
                }
                $row[] = "<a style='" . $expire . "' href='" . site_url() . "luumau/thanhly/form_thanhly?mau_id=" . $mau->id . "&ngaytl=" . $ngayluu . "'>Thanh Lý</a>";
                $data[] = $row;
            }
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $list->recordsTotal,
            "recordsFiltered" => $list->recordsFiltered,
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function detail($id) {
        $this->lang->load('mau');
        $this->parser->assign('languages', $this->lang->getLang());
        $this->curl->create($this->_api['nhanmau'] . 'getMau');
        $this->curl->post(array(
            'id' => $id,
        ));
        $mau = json_decode($this->curl->execute());
        if ($mau->mau->phongthinghiem != $this->session->userdata('ssAdminDonvi')) {
            redirect(site_url() . 'luumau/mau');
            exit;
        }
        $this->parser->assign('mau', get_object_vars($mau->mau));
        $dulieu = $this->mod_mau->get_kho($this->session->userdata('ssAdminDonvi'), 0);
        $this->curl->create($this->_api['nhansu'] . 'all_donvi');
        $this->curl->post(array());
        $list = json_decode($this->curl->execute());
        $donvi = array();
        foreach ($list->danhsach as $row) {
            if ($row->donvi_id != $this->session->userdata('ssAdminDonvi')) {
                $donvi[$row->donvi_id] = $row->donvi_ten;
            }
        }
        $this->parser->assign('donvi', $donvi);
        $tuluu = array();
        $tuluu[0] = "Chọn Tủ Lưu Mẫu";
        foreach ($dulieu as $row) {
            $tuluu[$row->kho_id] = $row->kho_name;
        }
        $this->parser->assign('tuluu', $tuluu);

        $danhsach_file = $this->mod_mau->danhsach_file($id);
        $danhsachfile = array();
        foreach ($danhsach_file as $item) {
            $this->curl->create($this->_api['general'] . 'get_file');
            $this->curl->post(array(
                'file_id' => $item->file_id
            ));
            $file = json_decode($this->curl->execute(), TRUE);
            $danhsachfile[] = base_url($file['site_url'] . $file['file'][0]['file_path']);
        }
        if(count($danhsach_file)=='0'){
            $danhsachfile = "";
        }
        $this->parser->assign('file', $danhsachfile);
        $this->parser->parse('mau/detail');
    }

    function get_kho() {
        $ngonngu = $this->lang->getLang();
        $kho_id = $this->input->post('kho_id');
        $level = $this->input->post('level');
        $mau_id = $this->input->post('mau_id');
        $key = $this->input->post('key');
        $level = $level + 1;
        $dulieu = $this->mod_mau->get_kho($this->session->userdata('ssAdminDonvi'), $kho_id);
        $dau = '<div id="' . $key . $level . '"><label>'. $ngonngu['chiamau_tuluu_level'] .' '. $level . '</label><select level="' . $level . '" onchange="myFunction(this,' . $level . ',\'' . $key . '\')" class="form-control" id="kho_id' . $kho_id . '" name="kho_id' . $kho_id . '">';
        $giua = "<option value='0'>".$ngonngu['chosen_tuluu']."</option>";
        foreach ($dulieu as $row) {
            $check = $this->mod_mau->get_check_kho($row->kho_id, $mau_id);
            if ($check == true) {
                $giua .= '<option value="' . $row->kho_id . '">' . $row->kho_name . '</option>';
            }
        }
        $cuoi = '</select></div>';
        if ($giua != "<option value='0'>".$ngonngu['chosen_tuluu']."</option>") {
            echo $dau . $giua . $cuoi;
        }
    }

    function detail_list($id, $luu) {
        $donvi = $this->input->get('donvi');
        $dulieu = $this->mod_mau->info_mau_chia($id);
        $dulieu1 = array();
        $totalKhoiLuong = 0;
        foreach ($dulieu as $row) {
            $dong = array();
            $dong['luumau_id'] = $row->luumau_id;
            $dong['luumau_name'] = $row->luumau_name;
            $dong['luumau_khoiluong'] = $row->luumau_khoiluong;
            $totalKhoiLuong = $totalKhoiLuong + $row->luumau_khoiluong;
            $dong['luumau_status'] = $row->luumau_status;
            $dong['luumau_goi'] = $row->luumau_goi;
            $dong['luumau_loai'] = $row->luumau_loai;
            if ($row->luumau_goi == 1) {
                $donvi_id = $this->mod_mau->donvi_luuho($row->luumau_id);
                $this->curl->create($this->_api['nhansu'] . 'get_donvi');
                $this->curl->post(array(
                    'donvi_id' => $donvi_id,
                ));
                $donvi = json_decode($this->curl->execute());
                $dong['donvi'] = $donvi->donvi_name;
            }
            $this->curl->create($this->_api['nhansu'] . 'nhansu_info');
            $this->curl->post(array(
                'nhansu_id' => $row->nhansu_id,
            ));
            $nhansu = json_decode($this->curl->execute());
            $dong['nhansu'] = $nhansu->nhansu[0]->nhansu_lastname . " " . $nhansu->nhansu[0]->nhansu_firstname;
            $vitri = "";
            $vi_tri = $this->mod_mau->vitri($row->kho_id);
            $catchuoi = explode("-", $vi_tri[0]->kho_ref);
            if (count($catchuoi) == 2) {
                $vitri = $vi_tri[0]->kho_name;
            } else {
                for ($i = 0; $i < count($catchuoi); $i++) {
                    if ($catchuoi[$i] != "") {
                        $info = $this->mod_mau->vitri($catchuoi[$i]);
                        if ($vitri == "") {
                            $vitri .= $info[0]->kho_name;
                        } else {
                            $vitri .= '&rightarrow;' . $info[0]->kho_name;
                        }
                    }
                }
                if ($vi_tri[0]->kho_name != "") {
                    $vitri .= '&rightarrow;' . $vi_tri[0]->kho_name;
                }
            }
            $dong['vitri'] = $vitri;
            $dulieu1[] = $dong;
        }
        $this->parser->assign('mau_luu', $luu);
        $this->parser->assign('totalKhoiLuong', $totalKhoiLuong);
        $this->parser->assign('donvi', $donvi);
        $this->parser->assign('mau_chia', $dulieu1);
        $this->parser->parse('mau/luu');
    }

    function luumau($id) {
        $this->curl->create($this->_api['nhanmau'] . 'luuMau');
        $this->curl->post(array(
            'id' => $id,
        ));
        $luu = json_decode($this->curl->execute());
        if (isset($luu->err_code) && $luu->err_code == 100) {
            redirect(site_url() . 'luumau/mau/detail/' . $id);
        }
    }

    function chiamau_add() {
        $items = $this->input->post();
        $items['nhansu_id'] = $this->session->userdata('ssAdminId');
        $kiemtra = $this->mod_mau->chiamau_add($items);
        if ($kiemtra == true) {
            echo '1';
        } else {
            echo '2';
        }
    }

    function hetmau() {
        $luumau_id = $this->input->post('luumau_id');
        $dulieu = $this->mod_mau->hetmau($luumau_id);
        ($dulieu == true) ? $a = "1" : $a = "2";
        echo $a;
    }

    function nhapmau() {
        $kho_id = $this->input->post('kho_id');
        $luumau_id = $this->input->post('luumau_id');
        $luumau_khoiluong = $this->input->post('luumau_khoiluong');
        $dulieu = $this->mod_mau->nhapmau($luumau_id, $kho_id, $luumau_khoiluong);
        ($dulieu == true) ? $a = "1" : $a = "2";
        echo $a;
    }

    function laymau() {
        $user_request = $this->input->post('user_request');
        $luumau_id = $this->input->post('luumau_id');
        $dulieu = $this->mod_mau->laymau($luumau_id, $user_request);
        ($dulieu == true) ? $a = "1" : $a = "2";
        echo $a;
    }

    function goiy_nhansu() {
        $tukhoa = $this->input->post('key');
        $this->curl->create($this->_api['nhansu'] . 'all_nhansu');
        $this->curl->post(array(
            'tukhoa' => $tukhoa,
            'post' => @$_POST
        ));
        $list = json_decode($this->curl->execute());
        foreach ($list->list as $row) {
            $output[] = array(
                "label" => $row->nhansu_lastname . ' ' . $row->nhansu_firstname,
                "info" => $row,
                "category" => ""
            );
        }
        echo json_encode($output);
    }

    function history($id) {
        $luumau = $this->mod_mau->info_luumau($id);
        $this->parser->assign('luumau', $luumau);
        $this->parser->parse('mau/history');
    }

    function history_list() {
        $luumau_id = $this->input->post('luumau_id');
        $list = $this->mod_mau->get_datatables($luumau_id);
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $no;
            switch ($item->history_action) {
                case '1':
                    $row[] = '<span class="blue">Nhập Mẫu</span>';
                    break;
                case '2':
                    $row[] = '<span class="green">Lấy Mẫu</span>';
                    break;
                case '3':
                    $row[] = '<span class="red">Hết Mẫu</span>';
                    break;
            }
            $row[] = $item->ngaythuchien;
            $this->curl->create($this->_api['nhansu'] . 'nhansu_info');
            $this->curl->post(array(
                'nhansu_id' => $item->user_action,
            ));
            $list = json_decode($this->curl->execute());
            $row[] = $list->nhansu[0]->nhansu_lastname . ' ' . $list->nhansu[0]->nhansu_firstname;

            $this->curl->create($this->_api['nhansu'] . 'nhansu_info');
            $this->curl->post(array(
                'nhansu_id' => $item->user_request,
            ));
            $list = json_decode($this->curl->execute());
            $row[] = $list->nhansu[0]->nhansu_lastname . ' ' . $list->nhansu[0]->nhansu_firstname;

            $noiluu = "";
            if ($item->kho_id != NULL) {
                $noiluu1 = $this->mod_mau->noiluumau($item->kho_id);
                $catchuoi = explode("-", $noiluu1[0]->kho_ref);
                for ($i = 0; $i < count($catchuoi); $i++) {
                    if ($catchuoi[$i] != "") {
                        $noiluu2 = $this->mod_mau->noiluumau($catchuoi[$i]);
                        if ($noiluu == "") {
                            $noiluu .= $noiluu2[0]->kho_name;
                        } else {
                            $noiluu .= '&rightarrow; ' . $noiluu2[0]->kho_name;
                        }
                    }
                }
                if ($noiluu == "") {
                    $noiluu = $noiluu1[0]->kho_name;
                } else {
                    $noiluu .= '&rightarrow; ' . $noiluu1[0]->kho_name;
                }
            } else {
                $noiluu = "-/-";
            }
            $row[] = $noiluu;
            $row[] = $item->history_khoiluong;
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_mau->count_all($donvi_id),
            "recordsFiltered" => $this->mod_mau->count_filtered($donvi_id),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function luuho_add() {
        $items = $this->input->post();
        $kiemtra = $this->mod_mau->luuho_add($items);
        if ($kiemtra == true) {
            echo '1';
        } else {
            echo '2';
        }
    }

    function uphinh_mau() {
        $file_id = $this->input->post('file_id');
        $mau_id = $this->input->post('mau_id');
        foreach ($file_id as $key => $value) {
            $this->mod_mau->uphinh_mau($mau_id, $value);
        }
    }
}
