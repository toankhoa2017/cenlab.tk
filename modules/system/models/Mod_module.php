<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('AccountInterface');
class Mod_module extends MY_Model implements AccountInterface {
    function _create($values) {
        if (!$this->db->insert('_module', array(
            'MOD_NAME' => $values['name'],
            'MOD_DEFINE' => $values['define'],
            'MOD_LINK' => $values['link'],
			'MOD_ORDER' => $values['order'],
            'GROUP_ID' => $values['group']
        ))) return FALSE;
        return TRUE;
    }
    function _setHide($items) {
        $update = array(
            'MOD_HIDE' => $items['MOD_HIDE'],
        );
        $this->db->where("MOD_ID",$items['MOD_ID']);
        return $this->db->update("_module", $update);
    }
    function _update($values) {
        $this->db->where('MOD_ID',$values['id']);
        $this->db->update('_module', array($values['column_name'] => $values['value']));
    }
}
/* End of file */