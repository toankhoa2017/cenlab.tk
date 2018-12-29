<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sparepart extends ADMIN_Controller{
    function __construct() {
        parent::__construct();
        $this->load->model('mod_sparepart');
        $this->load->model('mod_device');
    }
    
    function index(){
	$this->parser->assign('device', $this->mod_device->_getAll());
        $this->parser->parse('sparepart/list');
    }
    function listset() {
        $list = $this->mod_bothietbi->_getAll();
        $output = '';
        foreach($list as $bo) {
                $output.= '<option value="'.$bo['id'].'">'.$bo['set_name'].'</option>';
        }
        echo json_encode(array('list' => $output));
    }
    function ajax_list() {
        $list = $this->mod_sparepart->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $spa) {
            $no++;
            $row = array();
            $row[] = $spa->spa_code;
            $row[] = $spa->spa_name;
            $row[] = $spa->device_name;
            $row[] = "<button class='btn btn-minier btn-info' onclick=\"edit(".$spa->id.",'".$spa->spa_code."','".$spa->spa_name."',".$spa->device_id.")\"><i class='ace-icon fa fa-pencil smaller'></i></button>
                      <button class='btn btn-minier btn-danger' onclick=\"del(".$spa->id.")\"><i class='ace-icon fa fa-trash-o bigger-110'></i></button>";
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_sparepart->count_all(),
            "recordsFiltered" => $this->mod_sparepart->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function update() {
        $item = $this->input->post();
        $this->mod_sparepart->_update($item['spareid'], array('device_id' => $item['device'],
                                                            'spa_name' => $item['name'],
                                                            'spa_code' => $item['code']));
        echo json_encode(array('err_code' => '100'));
    }

    function ajax_add() {
        $item = $this->input->post();
        $this->mod_sparepart->_create(array(
                'device_id' => $item['device'],
                'spa_name' => $item['name'],
                'spa_code' => $item['code']));
        echo json_encode(array('err_code' => '100'));
    }
    
    function delete() {
        $this->mod_sparepart->_update($this->input->post('id'), array('status' => '2'));
        echo json_encode(array('err_code' => '100'));
    }
    function checkcode() {
        $code = $this->input->post('code');
        $kiemtra = $this->mod_sparepart->_check_code($code);
        if($kiemtra == true) {
            echo json_encode(array('err_code' => '100', 'err_mess' => 'Mã này đã tồn tại'));
        }
    }
}