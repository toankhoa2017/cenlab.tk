<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
get_instance()->load->iface('VattuInterface');

class Mod_loai extends MY_Model implements VattuInterface {
    var $table = 'loaisanpham';
    var $column = array('loai_id', 'loai_name', 'loai_symbol');
    var $order = array('loai_id' => 'DESC');


    private function get_datatables_query() {
        $this->db->from($this->table);
	$this->db->where('loai_status', '1');
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
        $this->db->where('loai_status', '1');
        return $this->db->count_all_results();
    }
    
    function _create($values) {
        $dieukien = array(
            'loai_symbol' => $values['loai_symbol']
        );
        $kiemtra = $this->db->select("*")->from($this->table)->where($dieukien)->get()->num_rows();
        if ($kiemtra == 0) {
            $insert = array(
                'loai_name' => $values['loai_name'],
                'loai_symbol' => $values['loai_symbol'],
            );
            return $this->db->insert($this->table, $insert);
        } else {
            return false;
        }
    }
    
    function sualoaisanpham($data){
        $kiemtra = $this->db->select('loai_id')->from($this->table)->where("loai_symbol='" . $data['loai_symbol'] . "' and loai_id!='" . $data['loai_id'] . "'")->get();
        if ($kiemtra->num_rows() == 0) {
            $this->db->where('loai_id', $data['loai_id']);
            return $this->db->update($this->table, $data);
        } else {
            return false;
        }
    }
    
	function _update($id,$data) {
		$this->db->where('loai_id', $id);
		$this->db->update($this->table, $data);
	}
}