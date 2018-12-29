<?php
class Api extends API_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model("mod_api");
    }
    /*
     * Lay mau
     * Input
     * * * * code
     * Output
     * * * * Thong tin mau
     */
    function getMau_post() {
        $id = ($this->post('id')) ? $this->post('id') : FALSE;
        $mau = $this->mod_api->_getMau($id);
        if ($mau) {
            $this->response(array('err_code' => '100', 'status' => 'success', 'mau' => $mau));
        }
        else $this->response(array('err_code' => '101', 'status' => 'fail'));
    }
    //Datatable
    function listMau_post() {
        $arrayPost = $this->post();
        $this->mod_api->donvi = $arrayPost['donvi'];
        $this->mod_api->post = $arrayPost['post'];
        $this->response(array(
            'list' => $this->mod_api->get_datatables(),
            'recordsTotal' => $this->mod_api->count_all(),
            'recordsFiltered' => $this->mod_api->count_filtered()
        ));
    }
    //End datatable
    function luuMau_post() {
        $id = $this->post('id');
        $mau = $this->mod_api->_luuMau($id);
        if ($mau) {
            $this->response(array('err_code' => '100', 'status' => 'success'));
        }
        else $this->response(array('err_code' => '101', 'status' => 'fail'));
    }
}