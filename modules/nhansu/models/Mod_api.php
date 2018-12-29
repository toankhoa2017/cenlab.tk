<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
get_instance()->load->iface('NhansuInterface');

class Mod_api extends MY_Model implements NhansuInterface {

    function demsodong() {
        $dulieu = $this->db->select('nhansu_id')->from('nhansu')->get();
        return $dulieu->num_rows();
    }

    function kiemtraemail($email) {
        $dieukien = array(
            'nhansu_email' => $email,
            'status' => '0'
        );
        $dulieu = $this->db->select('nhansu_id')->from('nhansu')->where($dieukien)->get();
        if ($dulieu->num_rows() == 0) {
            return true;
        } else {
            return false;
        }
    }

    function kiemtrasdt($sdt) {
        $dieukien = array(
            'nhansu_phone' => $sdt,
            'status' => '0'
        );
        $dulieu = $this->db->select('nhansu_id')->from('nhansu')->where($dieukien)->get();
        if ($dulieu->num_rows() == 0) {
            return true;
        } else {
            return false;
        }
    }

    function get_donvi($donvi_id) {
        $dulieu = $this->db->select("*")->from('donvi')->where('donvi_id', $donvi_id)->get();
        if ($dulieu->num_rows() > 0) {
            return $dulieu->result();
        } else {
            return false;
        }
    }

    function capbac_hientai($nhansu_id) {
        $dieukien = "a.nhansu_id=" . $nhansu_id . " and a.hopdong_id=b.hopdong_id";
        $dulieu = $this->db->select("b.*")->from("nhansu as a, hopdong as b")->where($dieukien)->get();
        return $dulieu->result();
    }

    function danhsach_donvi($donvi_id) {
        $dulieu = $this->db->select("*")->from("donvi")->where("donvi_id", $donvi_id)->get();
        return $dulieu->result();
    }

    function danhsach_theodonvi($donvi_id) {
        $dieukien = "a.donvi_id=" . $donvi_id . " and a.hopdong_id=b.hopdong_id and b.nhansu_status=0";
        $dulieu = $this->db->select('b.*')->from('nhansu as b, hopdong as a')->where($dieukien)->get();
        return $dulieu->result();
    }

    function danhsach_chucvu($chucvu_id) {
        $dulieu = $this->db->select("*")->from("chucvu")->where("chucvu_id", $chucvu_id)->get();
        return $dulieu->result();
    }

    function danhsach_theochucvu($chucvu_id, $donvi_id) {
        $dieukien = "a.chucvu_id=" . $chucvu_id . " and a.donvi_id=" . $donvi_id . " and a.hopdong_id=b.hopdong_id and b.nhansu_status=0";
        $dulieu = $this->db->select('b.*')->from('nhansu as b, hopdong as a')->where($dieukien)->get();
        if ($dulieu->num_rows() > 0) {
            return $dulieu->result();
        }
    }
    
    function list_donvi($donvi_id){
        $dieukien = "a.donvi_id=" . $donvi_id . " and a.hopdong_id=b.hopdong_id and b.nhansu_status=0";
        $dulieu = $this->db->select("*")->from("hopdong as a , nhansu as b")->where($dieukien)->get();
        return $dulieu->result();
    }
    
    function list_donvi_caocap($donvi_id,$quyen_id){
        $dieukien = "a.donvi_id=" . $donvi_id . " and b.nhansu_id=c.nhansu_id and c.quyen_id='".$quyen_id."' and a.hopdong_id=b.hopdong_id and b.nhansu_status=0";
        $dulieu = $this->db->select("*")->from("hopdong as a , nhansu as b, nhansu_quyen as c")->where($dieukien)->get();
        return $dulieu->result();
    }
    
    function danhsach_nhansu_tailieu($quyen_id){
        $dieukien = "a.quyen_id='".$quyen_id."' and a.nhansu_id=b.nhansu_id and b.nhansu_status=0";
        $dulieu = $this->db->select("*")->from("nhansu_quyen as a , nhansu as b")->where($dieukien)->get();
        return $dulieu->result();
    }
    
    function all_donvi(){
        $dulieu = $this->db->select("*")->from("donvi")->where(array("donvi_type" => "2", "donvi_status" => 1))->get();
        return $dulieu->result();
    }
    
    function all_donvi_full(){
        $dulieu = $this->db->select("*")->from("donvi")->get();
        return $dulieu->result();
    }
    
    function nhansu_info($id){
        $dulieu = $this->db->select("ns.*, dv.donvi_ten")->from("nhansu ns")
                ->join("hopdong hd", "ns.hopdong_id = hd.hopdong_id")
                ->join("donvi dv", "hd.donvi_id = dv.donvi_id")
                ->where("ns.nhansu_id",$id)
                ->get();
        if ($dulieu->num_rows() > 0) {
            return $dulieu->result();
        } else {
            return false;
        }
    }
    
    function all_nhansu($key){
        $dulieu = $this->db->select("*")->from("nhansu")->where('nhansu_status', '0')->like('nhansu_phone', $key)->or_like('nhansu_email', $key)->get();
        if ($dulieu->num_rows() > 0) {
            return $dulieu->result();
        } else {
            return false;
        }
    }
    
    function duyetketqua(){
        $dulieu = $this->db->select("*")->from("duyetketqua")->order_by("duyet_level", "asc")->get();
        return $dulieu->result();
    }
    
    function donvi_id($nhansu_id){
        $dieukien = array(
            "nhansu_id" => $nhansu_id,
            "hopdong_status" => '1'
        );
        $dulieu = $this->db->select("*")->from("hopdong")->where($dieukien)->get();
        return $dulieu->result();
    }

}

/* End of file */