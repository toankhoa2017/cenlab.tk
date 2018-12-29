<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
get_instance()->load->iface('LuumauInterface');

class Mod_mau extends MY_Model implements LuumauInterface {
    var $table = 'luumau';
    var $history = 'history';
    var $luuho = 'luuho';
    var $column = array('history_id', 'history_date');
    var $order = array('history_id' => 'asc');
    
    function chiamau_add($items) {
        $insert = array(
            'luumau_name' => $items['luumau_name'],
            'luumau_ngayvao' => date('Y-m-d H:i:s'),
            'luumau_loai' => $items['luumau_loai'],
            'luumau_khoiluong' => $items['luumau_khoiluong'],
            'luumau_status ' => '0',
            'mau_id' => $items['mau_id'],
            'nhansu_id' => $items['nhansu_id'],
            'kho_id' => $items['kho'],
        );
        $this->db->insert($this->table, $insert);
        $luumau_id = $this->db->insert_id();
        $history = array(
            'history_date' => date('Y-m-d H:i:s'),
            'history_action' => '1',
            'history_khoiluong' => $items['luumau_khoiluong'],
            'luumau_id' => $luumau_id,
            'kho_id' => $items['kho'],
            'user_action' => $items['nhansu_id'],
            'user_request' => NULL
        );
        return $this->db->insert($this->history, $history);
    }
    
    function info_mau_chia($id,$name){
        $dulieu = $this->db->select("*")->from($this->table)->where("mau_id",$id)->get();
        return $dulieu->result();
    }
    function ds_luumau_con($mau_id){
        $dieukien = array(
            "mau_id" => $mau_id,
            "luumau_status" => 0,// mau con trong kho
        );
        $dulieu = $this->db->select("*")->from($this->table)->where($dieukien)->get();
        return $dulieu->result();
    } 
    function info_luumau($id){
        $dulieu = $this->db->select("*")->from($this->table)->where("luumau_id",$id)->get();
        return $dulieu->result();
    }
    
    function get_kho($donvi_id,$kho_idparent){
        $dieukien = "donvi_id = '".$donvi_id."' and kho_idparent='".$kho_idparent."'";
        $dulieu = $this->db->select("*")->from('kho')->where($dieukien)->get();
        return $dulieu->result();
    }
    
    function get_check_kho($kho_id,$mau_id){
        $dieukien = array(
            'kho_id' => $kho_id,
            'luumau_status' => '0'
        );
        $kiemtra = $this->db->select("*")->from("luumau")->where($dieukien)->get();
        if($kiemtra->num_rows()>0){
            return false;
        }else{
            return true;
        }
    }
    
    function vitri($kho_id){
        $dulieu = $this->db->select("*")->from("kho")->where("kho_id",$kho_id)->get();
        return $dulieu->result();
    }
    
    function hetmau($luumau_id){
        $dulieu = array(
            'luumau_status' => '2',
            'luumau_khoiluong' => 0,
            'luumau_ngay_thanhly' => date('Y-m-d H:i:s')
        );
        $this->db->where("luumau_id",$luumau_id);
        $this->db->update($this->table,$dulieu);
        $history = array(
            'history_date' => date('Y-m-d H:i:s'),
            'history_action' => '3',
            'luumau_id' => $luumau_id,
            'kho_id' => NULL,
            'user_action' => $this->session->userdata('ssAdminId'),
            'user_request' => NULL
        );
        return $this->db->insert($this->history, $history);
    }
    
    function checkMauCon($mau_id){
        $dieukien = array(
            "mau_id" => $mau_id,
            "luumau_status !=" => 2,// mau con hết
        );
        $luumau = $this->db->select("*")->from($this->table)->where($dieukien)->get();
        $maumoi = $this->db->select("*")->from($this->table)->where("mau_id", $mau_id)->get();
        if($luumau->num_rows()>0 || $maumoi->num_rows() == 0){
            return TRUE;
        }else{
            return FALSE;
        }
    }
            
    function nhapmau($luumau_id,$kho_id, $luumau_khoiluong){
        $dulieu = array(
            'luumau_status' => '0',
            'kho_id' => $kho_id,
            'luumau_khoiluong' => $luumau_khoiluong,
            'nhansu_id' => $this->session->userdata('ssAdminId')
        );
        $this->db->where("luumau_id",$luumau_id);
        $this->db->update($this->table,$dulieu);
        $history = array(
            'history_date' => date('Y-m-d H:i:s'),
            'history_action' => '1',
            'history_khoiluong' => $luumau_khoiluong,
            'luumau_id' => $luumau_id,
            'kho_id' => $kho_id,
            'user_action' => $this->session->userdata('ssAdminId'),
            'user_request' => NULL
        );
        return $this->db->insert($this->history, $history);
    }
    
    function laymau($luumau_id,$user_request){
        $dulieu = array(
            'luumau_status' => '1',
            //'kho_id' => $kho_id,
            'nhansu_id' => $user_request
        );
        $this->db->where("luumau_id",$luumau_id);
        $this->db->update($this->table,$dulieu);
        $history = array(
            'history_date' => date('Y-m-d H:i:s'),
            'history_action' => '2',
            'luumau_id' => $luumau_id,
            'kho_id' => NULL,
            'user_action' => $this->session->userdata('ssAdminId'),
            'user_request' => $user_request,
        );
        return $this->db->insert($this->history, $history);
    }
    
    private function get_datatables_query($luumau_id) {
        $dieukien = "luumau_id='" . $luumau_id . "'";
        $this->db->from($this->history);
        $this->db->where($dieukien);
        $i = 0;
        foreach ($this->column as $item) {
            $tukhoa = trim(@$_POST['search']['value']);
            if ($tukhoa) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $tukhoa);
                } else {
                    $this->db->or_like($item, $tukhoa);
                }
                if (count($this->column) - 1 == $i)
                    $this->db->group_end();
            }
            $column[$i] = $item;
            $i++;
        }
        if (isset($_POST['order'])) {
            $this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } elseif (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables($luumau_id) {
        $this->get_datatables_query($luumau_id);
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->select("*,DATE_FORMAT(history_date, '%H:%i:%s %d/%m/%Y') as ngaythuchien")->get();
        return $query->result();
    }

    function count_filtered($luumau_id) {
        $this->get_datatables_query($luumau_id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all($luumau_id) {
        $dieukien = "luumau_id='" . $luumau_id . "'";
        $this->db->from($this->history)->where($dieukien);
        return $this->db->count_all_results();
    }
    
    function noiluumau($kho_id){
        $dulieu = $this->db->select("*")->from("kho")->where("kho_id",$kho_id)->get();
        return $dulieu->result();
    }
    
    function luuho_add($items) {
        $dulieu = array(
            'luumau_status' => '3',
            'luumau_ngayvao' => date('Y-m-d H:i:s'),
            'luumau_name' => $items['luumau_name'],
            'kho_id' => NULL,
            'luumau_loai' => $items['luumau_loai'],
            'mau_id' => $items['mau_id'],
            'luumau_khoiluong' => $items['luumau_khoiluong'],
            'nhansu_id' => $this->session->userdata('ssAdminId'),
            'luumau_goi' => '1'
        );
        $this->db->insert($this->table,$dulieu);
        $luumau_id = $this->db->insert_id();
        $data = array(
            'luuho_date' => date('Y-m-d H:i:s'),
            'luuho_loai ' => $items['luumau_loai'],
            'luuho_khoiluong' => $items['luumau_khoiluong'],
            'luuho_dieukien ' => $items['luuho_dieukien'],
            'luuho_status ' => '0',
            'mau_id' => $items['mau_id'],
            'nhansu_id' => $this->session->userdata('ssAdminId'),
            'donvi_id' => $items['donvi_id'],
            'luumau_id' => $luumau_id
        );
        return $this->db->insert($this->luuho, $data);
    }
    
    function check_luuho($mau_id){
        $dulieu = $this->db->select("*")->from($this->luuho)->where('mau_id',$mau_id)->get();
        if($dulieu->num_rows()>0){
            $dulieu = $dulieu->result();
            return $dulieu[0]->donvi_id;
        }else{
            return false;
        }
    }
    
    function check_mau($mau_id){
        $dieukien = array(
            'mau_id' => $mau_id,
            'luumau_goi' => '1'
        );
        $dulieu = $this->db->select("*")->from($this->table)->where($dieukien)->get();
        if($dulieu->num_rows()>0){
            return true;
        }else{
            return false;
        }
    }
    
    function donvi_luuho($luumau_id){
        $dulieu = $this->db->select("donvi_id")->from("luuho")->where("luumau_id",$luumau_id)->get()->result();
        return $dulieu[0]->donvi_id;
    }
    
    function uphinh_mau($mau_id,$file_id){
        $data = array(
            'mau_id' => $mau_id,
            'file_id' => $file_id
        );
        $this->db->insert('hinhmau',$data);
    }
    
    function danhsach_file($mau_id){
        $data = $this->db->select("*")->from('hinhmau')->where('mau_id',$mau_id)->get();
        return $data->result();
    }
    
    function thanhly_mau($items){
        $data = array(
            'luumau_status' => 2, // hết mẫu
            'luumau_ngay_thanhly' => date("Y-m-d")
        );
        $condition = $items["luumau_ids"];
        $this->db->where_in("luumau_id",$condition);
        $this->db->update($this->table, $data);
    }
    
    function getStatusMau($mau_id){
        $dieukien = array(
            "mau_id" => $mau_id,
            //"luumau_status" => 0,// mau con trong kho
        );
        $result = $this->db->select("*")->from($this->table)->where($dieukien)->get()->num_rows();
        return $result;
    }
    
    function dathanhly(){
        $dieukien = array(
            "luumau_ngay_thanhly !=" => NULL,
        );
        $result = $this->db->select("*")->from($this->table)->where($dieukien)->get()->result_array();
        return $result;
    }
}

/* End of file */