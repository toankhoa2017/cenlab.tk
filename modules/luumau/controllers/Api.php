<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends API_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("mod_api");
        $this->load->model("mod_mau");
    }
    
    private function getViTriLuuMau($kho_id){
        $vitri = "";
        $vi_tri = $this->mod_mau->vitri($kho_id);
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
        return $vitri;
    }
            
    function luuMau_post(){
        $input = $this->input->post();
        $userGiuMau = $result = $this->mod_api->getMaxLuumauMau($mau_id)[0];
        if($input["mau_id"]){
            if($userGiuMau["nhansu_id"] != $input['nhansu_id']){
                $result = $this->mod_api->luu_mau($input);
                if($result)
                    $this->response(array('err_code' => '200', 'mau_id' => $input["mau_id"]));
                else
                    $this->response(array('err_code' => '100', 'mau_id' => $input["mau_id"]));
            }else{
                $this->response(array('err_code' => '200', 'mau_id' => $input["mau_id"]));
            }
        }else{
            $this->response(array('err_code' => '100', 'mau_id' => NULL));
        }
    }
    
    function getStatusMau_post(){
        $mau_id = $this->input->post("mau_id");
        $result = $this->mod_api->getMaxLuumauMau($mau_id)[0];
        if($result){
            if(!$result["kho_id"]){
                $this->response(array('err_code' => '200', 'user_id' => $result["nhansu_id"]));
            }else{
                $this->response(array('err_code' => '200', 'kho_name' => $this->getViTriLuuMau($result["kho_id"])));
            }
        }else{
            $this->response(array('err_code' => '100', 'status' => ''));
        }
    }
}
