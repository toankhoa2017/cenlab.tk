<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
get_instance()->load->iface('VattuInterface');

class Mod_hang extends MY_Model implements VattuInterface {

    var $table = 'hang';
    var $column = array('hang_id', 'hang_name');
    var $order = array('hang_id' => 'DESC');


    private function get_datatables_query() {
        $this->db->from($this->table);
		$this->db->where('hang_status', '1');
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
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
    
    function _create($values) {
        $dieukien = array(
            'hang_name' => $values['hang_name'],
        );
        $kiemtra = $this->db->select("*")->from($this->table)->where($dieukien)->get()->num_rows();
        if ($kiemtra == 0) {
            $insert = array(
                'hang_name' => $values['hang_name'],
            );
            return $this->db->insert($this->table, $insert);
        } else {
            return false;
        }
    }
    
    function suahangsanxuat($data){
        $kiemtra = $this->db->select('hang_id')->from($this->table)->where("hang_name ='" . $data['hang_name '] . "' and hang_id!='" . $data['hang_id'] . "'")->get();
        if ($kiemtra->num_rows() == 0) {
            $this->db->where('hang_id', $data['hang_id']);
            return $this->db->update($this->table, $data);
        } else {
            return false;
        }
    }
	function _update($id, $values) {
		$this->db->where('hang_id',$id);
		$this->db->update($this->table, $values);
	}
    
}