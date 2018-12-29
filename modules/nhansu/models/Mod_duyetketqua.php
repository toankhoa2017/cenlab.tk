<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
get_instance()->load->iface('NhansuInterface');

class Mod_duyetketqua extends MY_Model implements NhansuInterface {

    function phongthinghiem() {
        $dulieu = $this->db->select("*")->from("donvi")->where(array("donvi_type" => "2", "donvi_status" => 1))->get();
        return $dulieu->result();
    }

    function nhansu() {
        $dulieu = $this->db->select("*")->from("nhansu")->where("nhansu_status", "1")->get();
        return $dulieu->result();
    }

    function nhansu_donvi($donvi_id) {
        $dieukien = "a.donvi_id='" . $donvi_id . "' and a.hopdong_status='1' and b.nhansu_status=1 and a.nhansu_id=b.nhansu_id";
        $dulieu = $this->db->select("*")->from("hopdong as a, nhansu as b")->where($dieukien)->get();
        return $dulieu->result();
    }

    function get_donvi($nhansu_id) {
        $dulieu = $this->db->select("*")->from("hopdong")->where("nhansu_id", $nhansu_id)->get()->result();
        return $dulieu[0]->donvi_id;
    }

    function luu_duyetketqua($donvi_id, $nhansu_id, $level) {
        $data = array(
            'duyet_level' => $level,
            'nhansu_id' => $nhansu_id,
            'donvi_id' => $donvi_id
        );
        //cấp 1
        if ((int) $level == 1) {
            $dieukien = "duyet_level='1' and donvi_id='" . $donvi_id . "'";
            $kiemtra = $this->db->select("*")->from("duyetketqua")->where($dieukien)->get();
            if ($kiemtra->num_rows() > 0) {
                $this->db->where($dieukien);
                $this->db->update("duyetketqua", $data);
            } else {
                $this->db->insert("duyetketqua", $data);
            }
        }
        //cấp 2 , cấp 3
        if ((int) $level > 1) {
            $dieukien = "duyet_level='" . $level . "'";
            $kiemtra = $this->db->select("*")->from("duyetketqua")->where($dieukien)->get();
            if ($kiemtra->num_rows() > 0) {
                $this->db->where($dieukien);
                $this->db->update("duyetketqua", $data);
            } else {
                $this->db->insert("duyetketqua", $data);
            }
        }
    }

    function dachon() {
        $dulieu = $this->db->select("*")->from("duyetketqua")->get();
        return $dulieu->result();
    }
    
    function nhansu_info($nhansu_id){
        $dulieu = $this->db->select("*")->from("nhansu")->where('nhansu_id',$nhansu_id)->get();
        return $dulieu->result();
    }

}

/* End of file */