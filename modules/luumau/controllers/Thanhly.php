<?php
class Thanhly extends ADMIN_Controller {

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
    
    function form_thanhly(){
        $mau_id = $this->input->get('mau_id');
        $ngayThanhLy = $this->input->get('ngaytl');
        $luumaus = $this->mod_mau->ds_luumau_con($mau_id);
        $luumau_array = array();
        foreach ($luumaus as $key => $row){
            $dong = array();
            $dong['luumau_id'] = $row->luumau_id;
            $dong['luumau_name'] = $row->luumau_name;
            $dong['luumau_khoiluong'] = $row->luumau_khoiluong;
            $dong['luumau_status'] = $row->luumau_status;
            $dong['luumau_goi'] = $row->luumau_goi;
            $dong['luumau_loai'] = $row->luumau_loai;
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
            $luumau_array[] = $dong;
        }
        $dulieu = $this->mod_mau->get_kho($this->session->userdata('ssAdminDonvi'), 0);
        $this->curl->create($this->_api['nhansu'] . 'nhansu_info');
        $this->curl->post(array(
            'nhansu_id' => $row->nhansu_id,
        ));
        $nhansu = json_decode($this->curl->execute());
        $dong['nhansu'] = $nhansu->nhansu[0]->nhansu_lastname . " " . $nhansu->nhansu[0]->nhansu_firstname;
        $tuluu = array();
        $tuluu[0] = "Chọn Tủ Lưu Mẫu";
        foreach ($dulieu as $row) {
            $tuluu[$row->kho_id] = $row->kho_name;
        }
        $this->parser->assign('luumaus', $luumau_array);
        $this->parser->assign('ngay_thanh_ly', $ngayThanhLy);
        $this->parser->assign('tuluu', $tuluu);
        $this->parser->parse('thanhly/dsthanhly');
    }
    
    function thanhly(){
        $items = $this->input->post();
        $this->mod_mau->thanhly_mau($items);
        echo 1;
    }
    
    function history_thanhly(){
        $mau_da_thanhly = $this->mod_mau->dathanhly();
        $mauInfos = array();
        foreach ($mau_da_thanhly as $m){
            if(!array_key_exists($m["mau_id"], $mauInfos)){
                $this->curl->create($this->_api['nhanmau'] . 'getMau');
                $this->curl->post(array(
                    'id' => $m["mau_id"],
                ));
                $mau = json_decode($this->curl->execute());
                $mauInfos[$m["mau_id"]] = get_object_vars($mau->mau);
            }
        }
        $this->parser->assign('mauInfos', $mauInfos);
        $this->parser->assign('mau_da_thanhly', $mau_da_thanhly);
        $this->parser->parse('thanhly/listthanhly');
    }
}    
