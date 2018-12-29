<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class MY_Controller extends CENLAB_Controller {
    function __construct() {
        parent::__construct();
		if (!$this->session->userdata('ssLang')) $this->session->set_userdata('ssLang', $this->config->item('language'));
        switch ($this->session->userdata('ssLang')) {
            case 'vni':
                $this->config->set_item('language', 'vietnamese');
                break;
            case 'eng':
                $this->config->set_item('language', 'english');
                break;
        }
        $this->parser->assign('host', $this->_host[0]);
        $this->parser->assign('assets_path', _ASSETS_PATH);
	}
}
class KH_Controller extends MY_Controller {
    function __construct() {
        parent::__construct();
		if (!$this->session->userdata('ssCustomerId')) redirect(site_url().'customer/login');
    }    
}
class ADMIN_Controller extends MY_Controller {
    var $_group;
    var $_module;
    function __construct() {
        parent::__construct();
        if (!$this->session->userdata('ssAccountId')) redirect(site_url().'admin/login');
        $this->parser->assign('list_group', $this->list_group);
        $this->parser->assign('list_module', $this->list_module);
        $this->load->model('tailieu_notification', 'tlnote');
        $this->load->model('ketqua_notification', 'kqnote');
        $kq_packege_export = $this->kqnote->count_package_export();
        $kq_all_approve = $this->kqnote->count_all_approve($this->session->userdata('ssAdminId'), TRUE);
        $this->parser->assign('ketqua', $kq_packege_export + $kq_all_approve);
        $this->parser->assign('tailieu',$this->tlnote->CountTaiLieu());
        $this->_group = $this->uri->segment(1);
        $this->_module = $this->uri->segment(2);
        $this->parser->assign('ssLink', array(
            'group' => $this->_group,
            'module' => $this->_module
        ));

        $this->parser->assign('ssAdmin', array(
            'ssAdminId' => $this->session->userdata('ssAdminId'),
            'ssAdminFullname' => $this->session->userdata('ssAdminFullname'),
            'ssAccountId' => $this->session->userdata('ssAccountId'),
        ));
        //$this->_module = $this->router->fetch_module();
    }
}
class API_Controller extends REST_Controller {
    function __construct() {
        parent::__construct();
	}
}
/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */