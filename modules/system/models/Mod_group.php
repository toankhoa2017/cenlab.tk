<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('AccountInterface');
class Mod_group extends MY_Model implements AccountInterface {
    var $project = FALSE;
    var $table = '_group';
    var $column = array('GROUP_NAME','GROUP_ICON', 'GROUP_ORDER'); //set column field database for order and search
    var $order = array('GROUP_ORDER' => 'ASC'); // default order 

    //Datatable
    private function _get_datatables_query() {
        $this->db->from($this->table);
        $this->db->where('PROJECT_ID', $this->project);
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
		$this->db->where('PROJECT_ID', $this->project);
        return $this->db->count_all_results();
    }
    function _create($values) {
        if (!$this->db->insert($this->table, array(
            'GROUP_NAME' => $values['name'],
            'GROUP_LINK' => $values['link'],
            'GROUP_ICON' => $values['icon'],
			'GROUP_ORDER' => $values['order'],
            'PROJECT_ID' => $values['project']
        ))) return FALSE;
        return TRUE;
    }
	function _update($values) {
		$this->db->where('GROUP_ID',$values['id']);
        $this->db->update($this->table, array(
			'GROUP_NAME' => $values['gname'],
            'GROUP_LINK' => $values['glink'],
            'GROUP_ICON' => $values['gicon'],
			'GROUP_ORDER' => $values['gorder']));	
	}
    function _get($id) {
        $this->db->select('*');
        $this->db->from('_group');
        $this->db->where('GROUP_ID', $id);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
		return ($result) ? $result : FALSE;        
    }
    function _getmods($id) {
        $this->db->select('*');
        $this->db->from('_module');
        $this->db->where('GROUP_ID', $id);
        $this->db->order_by('MOD_ORDER', 'ASC');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
		return ($result) ? $result : FALSE;        
    }
    function _delmod($items) {
        if (!$this->db->delete('_module', array('MOD_ID' => $items['mid'], 'GROUP_ID' => $items['gid']))) {
            return FALSE;
        }
        return TRUE;
    }
}
/* End of file */