<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');

class Mod_khachhang extends MY_Model implements NenmauInterface {
    var $table = 'mau_dongia_khachhang';
    var $table_chatgia = 'mau_chatgia';
    function getGiaByKhachhang($khachhang){
        $query = $this->db->select("dongia_id, price")->from($this->table)->where(array("khachhang_id" => $khachhang))->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    
    function _setgia($data) {
        $dieukien = array(
            "dongia_id" => $data["dongia"],
            "khachhang_id" => $data["khachhang"]
        );
        $update = array(
            "price" => $data["gia"]
        );
        $dulieu = $this->db->select("*")->from($this->table)->where($dieukien)->get();
        if ($dulieu->num_rows() > 0) {
            $this->db->where($dieukien);
            $this->db->update($this->table, $update);
        }else{
            $dataInsert = array(
                "dongia_id" => $data["dongia"],
                "khachhang_id" => $data["khachhang"],
                "price" => $data["gia"]
            );
            $this->db->insert($this->table, $dataInsert);
        }
    }
    
    function _setgia_don($data) {
        $dieukien = array(
            'bo_id' => $data[0]["bo_id"],
            'khachhang_id' => $data[0]["khachhang_id"]
        );
        $this->db->where($dieukien);
        $this->db->delete($this->table_chatgia);
        return $this->db->insert_batch($this->table_chatgia, $data);
    }
}
