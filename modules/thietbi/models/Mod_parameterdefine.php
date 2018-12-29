<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('ThietbiInterface');
class Mod_parameterdefine extends MY_Model implements ThietbiInterface {
    var $table = 'parameterdefine'; 
    var $column = array('a.type_name','b.name','b.unit','b.max_value','b.min_value','b.decimal_point','b.required'); //set column field database for order and search
    var $order = array('b.id' => 'ASC');
    var $idtype = false;
	
    private function _get_datatables_query() {
		$this->db->join('devicesettype a', 'a.type_id=b.device_set_type_id','left');
        $this->db->from('parameterdefine b');
		$this->db->where('b.status', '1');
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
        $this->db->where('id',$id);
        $this->db->update($this->table,$values);
    }
	function _getAll() {
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where('status','1');
		$query = $this->db->get();
		$result = $query->result_array();
		$query->free_result();
		return ($result) ? $result : false;
	}
	function _getMaxid($id) {
		$this->db->select('MAX(serial_no) AS max');
		$this->db->from($this->table);
		$this->db->where('device_set_type_id',$id);
		$query = $this->db->get();
        $result = $query->row_array();
        if ($result && count($result) > 0) return $result['max'];
        return 0;
	}
}
