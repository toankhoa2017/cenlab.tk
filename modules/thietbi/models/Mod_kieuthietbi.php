<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('ThietbiInterface');
class Mod_kieuthietbi extends MY_Model implements ThietbiInterface {
    var $table = 'devicesettype'; 
    var $column = array('type_name','checking_period','checking_daily'); //set column field database for order and search
    var $order = array('type_id' => 'ASC');
    
    private function _get_datatables_query() {
        $this->db->from($this->table);
		$this->db->where('status', '1');
        $i = 0;
        foreach ($this->column as $item) {//loop column 
            if (@$_POST['search']['value']) {//if datatable send POST for search
                if ($i === 0 ) {//first loop
                    $this->db->group_start(); //open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND. 
                    $this->db->like($item, $_POST['search']['value']);
                }
                else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                //last loop
                if (count($this->column) - 1 == $i) $this->db->group_end(); //close bracket
            }
            $column[$i] = $item; //set column array variable to order processing
            $i++;
        }
        if (isset($_POST['order'])) {//here order processing
            $this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        elseif (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    function get_datatables() {
        $this->_get_datatables_query();
        if (@$_POST['length'] != -1)
        $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
    function count_all() {
        $this->db->from($this->table);
		$this->db->where('status','1');
        return $this->db->count_all_results();
    }
    
    function _create($values) {
        if(!$this->db->insert($this->table,$values)) return FALSE;
        return TRUE;
    }
    
    function _update($id, $values) {
        $this->db->where('type_id',$id);
        $this->db->update($this->table,$values);
    }
}
