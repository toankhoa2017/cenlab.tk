<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
get_instance()->load->iface('LuumauInterface');

class Mod_luuho extends MY_Model implements LuumauInterface {
    
    var $table = 'luuho as a, luumau as b';
    var $column = array('b.luumau_name','a.luuho_loai' , 'a.luuho_khoiluong' , 'a.luuho_dieukien'); //set column field database for order and search
    var $order = array(); // default order 

    private function get_datatables_query($donvi_id) {
        $dieukien = "a.donvi_id='" . $donvi_id . "' and a.luumau_id=b.luumau_id";
        $this->db->select("a.mau_id, a.luumau_id");
        $this->db->DISTINCT();
        $this->db->from($this->table);
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

    function get_datatables($donvi_id) {
        $this->get_datatables_query($donvi_id);
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($donvi_id) {
        $this->get_datatables_query($donvi_id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all($donvi_id) {
        $dieukien = "a.donvi_id='" . $donvi_id . "' and a.luumau_id=b.luumau_id";
        $this->db->select("a.mau_id");
        $this->db->DISTINCT();
        $this->db->from($this->table)->where($dieukien);
        return $this->db->count_all_results();
    }
    
    function get_kho($donvi_id,$kho_idparent){
        $dieukien = "donvi_id = '".$donvi_id."' and kho_idparent='".$kho_idparent."'";
        $dulieu = $this->db->select("*")->from('kho')->where($dieukien)->get();
        return $dulieu->result();
    }
    
    function info_mau_chia($id){
        $dieukien = "b.mau_id = '".$id."' and a.luumau_id = b.luumau_id";
        $dulieu = $this->db->select("*,a.nhansu_id as nhansu")->from("luumau as a , luuho as b")->where($dieukien)->get();
        return $dulieu->result();
    }
    
    function info_luuho($mau_id){
        $dulieu = $this->db->select("*")->from("luuho")->where(array("mau_id" => $mau_id, "luuho_status !=" => 2))->get();
        if($dulieu->num_rows()>0){
            return $dulieu->result();
        }else{
            return 0;
        }
    }
    
    function danhsach_file($mau_id){
        $data = $this->db->select("*")->from('hinhmau')->where('mau_id',$mau_id)->get();
        return $data->result();
    }
    
    function luukho($item){
        $dulieu = array(
            'kho_id' => $item["kho_id"],
            'luuho_status' => 1
        );
        $dieukien = array(
            "mau_id" => $item["mau_id"],
            "luuho_status" => 0
        );
        $this->db->where($dieukien);
        return $this->db->update("luuho", $dulieu);
    }
    
}

/* End of file */