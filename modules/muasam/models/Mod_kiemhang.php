<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
get_instance()->load->iface('VattuInterface');

class Mod_kiemhang extends MY_Model implements VattuInterface {

    var $table = 'denghi';
    var $column = array('denghi_id', 'denghi_title', 'denghi_date');
    var $order = array('denghi_id' => 'DESC');

    private function get_datatables_query() {
        $dieukien = array(
            "nhansu_nhan" => $this->session->userdata('ssAdminId'),
            "quytrinh_id" => '5'
        );
        $this->db->from($this->table)->where($dieukien);
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

    function get_datatables() {
        $this->get_datatables_query();
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered() {
        $this->get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all() {
        $dieukien = array(
            "nhansu_nhan" => $this->session->userdata('ssAdminId'),
            "quytrinh_id" => '4'
        );
        $this->db->from($this->table)->where($dieukien);
        return $this->db->count_all_results();
    }
    
    function denghi_success($denghi_id){
        $kiemtra = $this->db->select("*")->from("denghi")->where("denghi_id",$denghi_id)->get();
        if($kiemtra->num_rows()>0){
            $this->db->where("denghi_id",$denghi_id);
            $this->db->update("denghi",array('denghi_success'=>'2','denghi_approve'=>'1'));
            return $kiemtra->row();
        }else{
            return false;
        }
    }
    
    function create_detail($data){
        return $this->db->insert('denghi_detail', $data);
    }
    
    function update_denghi($denghi_id,$denghi_describe){
        $data = array(
            'denghi_describe' => $denghi_describe,
            'denghi_approve' => '2'
        );
        $this->db->where('denghi_id',$denghi_id);
        $this->db->update('denghi',$data);
        $this->db->where('denghi_id',$denghi_id);
        $this->db->delete('denghi_detail');
    }
    
}