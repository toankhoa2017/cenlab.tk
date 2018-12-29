<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');

class Mod_chitieu extends MY_Model implements NenmauInterface {

    var $chitieu_table = "mau_chitieu";
    var $chitieu_nenmau = "mau_nenmau_chitieu";
    var $phuongphap_table = "mau_phuongphap";
    var $kythuat_table = "mau_kythuat";
    var $nenmau_table = "mau_nenmau";
    var $phongthinghiem_table = "phongthinghiem";
    var $donvitinh_table = "mau_donvitinh";
    var $dongia = "mau_dongia";
    var $congnhan_table = "mau_congnhan";
    
    //Bao Dinh
    function taochitieu($values) {
        $chitieu = array(
            'chitieu_name' => $values['chitieu'],
            'chitieu_describe' => $values['mota'],
            'chitieu_name_eng' => $values['chitieu_eng'],
            'kqkf' => ($values['kqkf'] == "yes") ? true : false,
            'donvitinh_id' => $values['donvi'],
        );
        $this->db->insert('mau_chitieu', $chitieu);
        return $this->db->insert_id();
    }
    function themvaonenmau($chitieu, $values) {
        $nenmau_chitieu = array(
            'nenmau_id' => $values['nenmau'],
            'chitieu_id' => $chitieu,
            'thoigian' => $values['thoigian_luu']
        );
        $this->db->insert('mau_nenmau_chitieu', $nenmau_chitieu);        
    }
    //End Bao Dinh
    // search autocomplete
    function goiy_chitieu($key) {
        $dulieu = $this->db->select("*")->from("mau_chitieu")->where('chitieu_status', '1')->like('chitieu_name', $key)->get();
        if ($dulieu->num_rows() > 0) {
            return $dulieu->result();
        } else {
            return false;
        }
    }
    ///////////////
    function get_chitieu($chitieu_id, $nenmau_id, $package_code) {
        $dieukien = "a.package_code='" . $package_code . "' and a.chitieu_id='" . $chitieu_id . "' and a.nenmau_id='" . $nenmau_id . "' and a.kythuat_id=d.kythuat_id and a.chitieu_id=b.chitieu_id";
        $dulieu = $this->db->select("*")->from("mau_dongia as a, mau_chitieu as b, mau_kythuat as d")->where($dieukien)->join('mau_phuongphap', 'mau_phuongphap.phuongphap_id = a.phuongphap_id', 'left')->get();
        return $dulieu->result();
    }
    function donvi() {
        $dieukien = array(
            'donvitinh_status' => '1',
            'donvitinh_type' => '1'
        );
        $dulieu = $this->db->select("*")->from($this->donvitinh_table)->where($dieukien)->get();
        return $dulieu->result();
    }
    
    function kiemtra_kythuat($name_kythuat, $id_kythuat) {
        $dieukien = array(
            'kythuat_name' => $name_kythuat,
            'kythuat_id' => $id_kythuat
        );
        $dulieu = $this->db->select("*")->from($this->kythuat_table)->where($dieukien)->get();
        if ($dulieu->num_rows() > 0) {
            return $id_kythuat;
        } else {
            $insert = array(
                'kythuat_name' => $name_kythuat,
            );
            $this->db->insert($this->kythuat_table, $insert);
            return $this->db->insert_id();
        }
    }
    function kiemtra_chitieu($idcu, $tenchitieu, $tenchitieu_eng, $mota) {
        $dieukien = array(
            "chitieu_id" => $idcu,
        );
        $data = array(
            "chitieu_name" => $tenchitieu,
            "chitieu_describe" => $mota,
            "chitieu_name_eng" => $tenchitieu_eng
        );
        $this->db->where($dieukien);
        $this->db->update($this->chitieu_table, $data);
    }

    function update_dongia($data) {
        $dieukien = array(
            "package_code" => $data['package_code'],
        );
        $this->db->where($dieukien);
        return $this->db->update($this->dongia, $data);
    }
    function capnhat_gia($gia, $chitieu, $nenmau) {
        $dieukien = array(
            "chitieu_id" => $chitieu,
            "nenmau_id" => $nenmau
        );
        $update = array(
            "price" => $gia
        );
        $this->db->where($dieukien);
        $this->db->update($this->dongia, $update);
    }
    function update_thoigianluu($chitieu_id, $nenmau_id, $thoigian) {
        $dieukien = array(
            'nenmau_id' => $nenmau_id,
            'chitieu_id' => $chitieu_id,
        );
        $update = array(
            'thoigian' => $thoigian
        );
        $this->db->where($dieukien);
        $this->db->update($this->chitieu_nenmau, $update);
    }
    function xoa_chitieu($data) {
        $update_chitieu = array(
            'chitieu_status' => '2'
        );
        $this->db->where('chitieu_id', $data['chitieu_id']);
        $this->db->update($this->chitieu_table, $update_chitieu);
        $this->db->where($data);
        $this->db->update('mau_dongia', array('package_status' => '2'));
        $this->db->where($data);
        $this->db->delete($this->chitieu_nenmau);
    }
    function congnhan() {
        $dulieu = $this->db->select("*")->from($this->congnhan_table)->where("congnhan_dateend >= CURDATE() and congnhan_status=1")->get();
        return $dulieu->result();
    }

    function phuongphap() {
        $dulieu = $this->db->select("*")->from($this->phuongphap_table)->where("phuongphap_status", "1")->get();
        return $dulieu->result();
    }

    function kythuat() {
        $dulieu = $this->db->select("*")->from($this->kythuat_table)->where("kythuat_status", "1")->get();
        return $dulieu->result();
    }
}
