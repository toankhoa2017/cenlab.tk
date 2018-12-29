<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deviceset extends ADMIN_Controller{
    function __construct() {
        parent::__construct();
        $this->load->model('mod_kieuthietbi');
		$this->load->model('mod_bothietbi');
    }
    
    function index(){
       
    }
  
    function ajax_listset() {
	$this->mod_bothietbi->idtype = $this->input->get('id');
        $list = $this->mod_bothietbi->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $bo) {
            $no++;
            $row = array();
            $row[] = $bo->set_name;
            $row[] = $bo->checking_period;
            if($bo->checking_daily == 1) {
                $row[] = "Có";
            }
            else {
                $row[] = "Không";
            }
			if($bo->device_status == 1) {
				$row[] = "Tồn kho";
			}
			else if($bo->device_status == 2) {
				$row[] = "Đang kiểm";
			}
			else if($bo->device_status == 3) {
				$row[] = "Chờ sửa";
			}
			else if($bo->device_status == 4) {
				$row[] = "Đang sửa";
			}
			else if($bo->device_status == 5) {
				$row[] = "Đang xuất";
			}
			else {
				$row[] = "Thanh lý";
			}
            $row[] = "<button class='btn btn-minier btn-info' onclick=\"edit(".$bo->id.",'".$bo->set_name."',".$bo->checking_period.",".$bo->checking_daily.",".$bo->device_status.")\"><i class='ace-icon fa fa-pencil smaller'></i></button>
                      <button class='btn btn-minier btn-danger' onclick=\"del(".$bo->id.")\"><i class='ace-icon fa fa-trash-o bigger-110'></i></button>";
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_bothietbi->count_all(),
            "recordsFiltered" => $this->mod_bothietbi->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function update() {
        $item = $this->input->post();
        $this->mod_bothietbi->_update($item['setid'], array('set_name' => $item['name'],
                										 'checking_period' => $item['time_check'],
                										 'checking_daily' => $item['check'],
                										 'device_status' => $item['status'],
                                                         'update_date' => date('Y-m-d H:i:s')));
        echo json_encode(array('err_code' => '100'));
    }

    function addset() {
        $item = $this->input->post();
        $this->mod_bothietbi->_create(array(
                'set_name' => $item['name'],
                'device_set_type_id' => $item['type'],
                'checking_period' => $item['time_check'],
                'checking_daily' => $item['check'],
                'device_status' => $item['status'],
                'create_date' => date('Y-m-d H:i:s')));
        echo json_encode(array('err_code' => '100'));
    }
    
    function delete() {
        $this->mod_bothietbi->_update($this->input->post('id'), array('status' => '2'));
        echo json_encode(array('err_code' => '100'));
    }
}