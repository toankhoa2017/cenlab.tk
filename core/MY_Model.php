<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
class MY_Model extends CI_Model
{
    var $_module;
    public function __construct() {
        parent::__construct();
        //$this->_module = $this->router->fetch_module();
    }
    function _checkExists($table, $field, $val) {
        $this->db->where($field, $val);
        $query = $this->db->get($table);
        if ($query->num_rows() > 0) {
            $query->free_result();
            return TRUE;
        }
        $query->free_result();
        return FALSE;
    }	
    function _getField($table, $field, $cond) {
        $this->db->select("{$field} AS field");
        $this->db->from($table);
        $this->db->where($cond);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result['field'] : FALSE;
    }
    function _getFields($table, $fields, $cond) {
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->where($cond);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
}
// END MY_Model Class

/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */