<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends API_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("mod_api");
    }

    function rand_matkhau($length) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $size = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0, $size - 1)];
        }
        return $str;
    }

    function email_get() {
        $email = $this->input->get("email");
        $kt_email = $this->mod_api->kiemtraemail($email);
        if ($kt_email == true) {
            $err = "200"; //không trùng
        } else {
            $err = "101"; //trùng rồi
        }
        echo json_encode(array("err_code" => $err));
    }

    function phone_get() {
        $sdt = $this->input->get("phone");
        $kt_sdt = $this->mod_api->kiemtrasdt($sdt);
        if ($kt_sdt == true) {
            $err = 200;
        } else {
            $err = 101;
        }
        echo json_encode(array("err_code" => $err));
    }

    function register_post() {
        $email = $this->input->post('email');
        $sdt = $this->input->post('phone');
        $kt_email = $this->mod_api->kiemtraemail($email);
        $kt_sdt = $this->mod_api->kiemtrasdt($sdt);
        if ($kt_email == true && $kt_sdt == true) {
            $countid = $this->mod_api->demsodong();
            $countid = $countid + 1;
            $matkhautam = $this->rand_matkhau(6);
            $err = "200";
        } else {
            if ($kt_email == true) {
                $countid = "";
                $matkhautam = "";
                $err = "102";
            } elseif ($kt_email == false) {
                $countid = "";
                $matkhautam = "";
                $err = "103";
            } else {
                $countid = "";
                $matkhautam = "";
                $err = "101";
            }
        }
        echo json_encode(array('err_code' => $err, 'id' => $countid, 'pwd' => $matkhautam));
    }

    function get_donvi_post() {
        $donvi = $this->input->post('donvi_id');
        $dulieu = $this->mod_api->get_donvi($donvi);
        if ($dulieu == true) {
            $this->response(array('err_code' => '200', 'donvi_name' => $dulieu[0]->donvi_ten));
        } else {
            $this->response(array('err_code' => '101', 'donvi_name' => ''));
        }
    }

    function captren_post() {
        $danhsach_id_nhansu_captren = array();
        $input = $this->input->post();
        if (isset($input['nhansu_id'])) {
            $hientai = $this->mod_api->capbac_hientai($input['nhansu_id']);
            //chức vụ cấp cao hơn
            $chucvu = $this->mod_api->danhsach_chucvu($hientai[0]->chucvu_id);
            $laychucvucaohon = explode("-", $chucvu[0]->chucvu_ref);
            $ketthuc = 0;
            if (count($laychucvucaohon) != 0) {
                for ($i = 1; $i < count($laychucvucaohon); $i++) {
                    if (trim($laychucvucaohon[$i]) != "") {
                        $get_danhsach_theochucvu = $this->mod_api->danhsach_theochucvu(trim($laychucvucaohon[$i]), $hientai[0]->donvi_id);
                        if ($ketthuc == 0) {
                            foreach ($get_danhsach_theochucvu as $row) {
                                if (!in_array($row->nhansu_id, $danhsach_id_nhansu_captren)) {
                                    $danhsach_id_nhansu_captren[$row->nhansu_id] = $row->nhansu_lastname . " " . $row->nhansu_firstname;
                                    $ketthuc = 1;
                                }
                            }
                            $get_danhsach_theochucvu = $this->mod_api->danhsach_theochucvu($input['nhansu_id'], $hientai[0]->donvi_id);
                            foreach ($get_danhsach_theochucvu as $row) {
                                if (!in_array($row->nhansu_id, $danhsach_id_nhansu_captren)) {
                                    $danhsach_id_nhansu_captren[$row->nhansu_id] = $row->nhansu_lastname . " " . $row->nhansu_firstname;
                                    $ketthuc = 1;
                                }
                            }
                        }
                    }
                }
                //kết thúc chức vụ cấp cao hơn
                if (count($danhsach_id_nhansu_captren) > 0) {
                    $this->response(array('err_code' => '200', 'danhsach' => $danhsach_id_nhansu_captren));
                } else {
                    $this->response(array('err_code' => '101'));
                }
            }
        } else {
            $this->response(array('err_code' => '101'));
        }
    }

    function danhsach_nhansu_donvi_post() {
        $danhsach = array();
        $input = $this->input->post();
        $hientai = $this->mod_api->capbac_hientai($input['nhansu_id']);
        //chức vụ cấp cao hơn
        $chucvu = $this->mod_api->danhsach_chucvu($hientai[0]->chucvu_id);
        $laychucvucaohon = explode("-", $chucvu[0]->chucvu_ref);
        $ketthuc = 0;

        if (count($laychucvucaohon) != 0) {
            for ($i = 1; $i < count($laychucvucaohon); $i++) {
                if (trim($laychucvucaohon[$i]) != "") {
                    $get_danhsach_theochucvu = $this->mod_api->list_donvi(trim($laychucvucaohon[$i]));
                    foreach ($get_danhsach_theochucvu as $row) {
                        if (!in_array($row->nhansu_id, $danhsach)) {
                            $danhsach[$row->nhansu_id] = $row->nhansu_lastname . " " . $row->nhansu_firstname;
                        }
                    }
                }
            }
        }
        $this->response(array('err_code' => '200', 'danhsach' => $danhsach));
    }

    function danhsach_nhansu_tailieu_post() {
        $quyen_id = $this->input->post('quyen_id');
        $dulieu = $this->mod_api->danhsach_nhansu_tailieu($quyen_id);
        if ($dulieu == true) {
            $this->response(array('err_code' => '200', 'danhsach' => $dulieu));
        } else {
            $this->response(array('err_code' => '101', 'danhsach' => $dulieu));
        }
    }

    function danhsach_duyetdenghi_post() {
        $danhsach = array();
        $input = $this->input->post();
        $hientai = $this->mod_api->capbac_hientai($input['nhansu_id']);
        //chức vụ cấp cao hơn
        $chucvu = $this->mod_api->danhsach_chucvu($hientai[0]->chucvu_id);
        $laychucvucaohon = explode("-", $chucvu[0]->chucvu_ref);
        $ketthuc = 0;

        if (count($laychucvucaohon) != 0) {
            for ($i = 1; $i < count($laychucvucaohon); $i++) {
                if (trim($laychucvucaohon[$i]) != "") {
                    $get_danhsach_theochucvu = $this->mod_api->list_donvi_caocap(trim($laychucvucaohon[$i]), $input['quyen_id']);
                    foreach ($get_danhsach_theochucvu as $row) {
                        if (!in_array($row->nhansu_id, $danhsach)) {
                            $danhsach[$row->nhansu_id] = $row->nhansu_lastname . " " . $row->nhansu_firstname;
                        }
                    }
                }
            }
        }
        $this->response(array('err_code' => '200', 'danhsach' => $danhsach));
    }

    function all_donvi_post() {
        $dulieu = $this->mod_api->all_donvi();
        $this->response(array('err_code' => '200', 'danhsach' => $dulieu));
    }

    function all_donvi_full_post() {
        $dulieu = $this->mod_api->all_donvi_full();
        $this->response(array('err_code' => '200', 'danhsach' => $dulieu));
    }

    function nhansu_info_post() {
        $id = $this->input->post('nhansu_id');
        $dulieu = $this->mod_api->nhansu_info($id);
        if ($dulieu == true) {
		foreach($dulieu as &$nhansu){
			if($nhansu->nhansu_sign){
				$nhansu->nhansu_sign = './'._UPLOADS_URL.'nhansu/sign/'.$nhansu->nhansu_sign;
			}
		}
            $this->response(array('err_code' => '200', 'nhansu' => $dulieu));
        } else {
            $this->response(array('err_code' => '101'));
        }
    }

    function all_nhansu_post() {
        $key = $this->input->post("tukhoa");
        $dulieu = $this->mod_api->all_nhansu($key);
        $this->response(array('err_code' => '200', 'list' => $dulieu));
    }

    function duyetketqua_post() {
        $input = $this->input->post('nhansu_id');
        if ($input > 0) {
            $donvi = $this->mod_api->donvi_id($input);
            if ($donvi[0]->donvi_id == "") {
                $this->response(array('err_code' => '101'));
            } else {
                $dulieu = $this->mod_api->duyetketqua();
                $data = array();
                foreach ($dulieu as $row) {
					if ($row->duyet_level > 1) {
                        $danhsach = $this->mod_api->nhansu_info($row->nhansu_id);
                        $info = array();
                        foreach($danhsach as $abc){
                            $info[] = array(
                                'id' => $abc->nhansu_id, 'name' => $abc->nhansu_lastname . ' ' . $abc->nhansu_firstname ,'file_id' => $row->file_id
                            );
                        }
                        $data[$row->duyet_level] = ($info);
                    }
					/*
                    if (($row->duyet_level == '1' && $row->donvi_id == $donvi[0]->donvi_id) || $row->duyet_level > 1) {
                        $danhsach = $this->mod_api->nhansu_info($row->nhansu_id);
                        $info = array();
                        foreach($danhsach as $abc){
                            $info[] = array(
                                'id' => $abc->nhansu_id, 'name' => $abc->nhansu_lastname . ' ' . $abc->nhansu_firstname ,'file_id' => $row->file_id
                            );
                        }
                        $data[$row->duyet_level] = ($info);
                    }
					*/
                }
                $this->response(array('err_code' => '200', 'list' => $data));
            }
        } else {
            $this->response(array('err_code' => '101'));
        }
    }

}
