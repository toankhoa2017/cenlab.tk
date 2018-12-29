<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Devicetype extends ADMIN_Controller{
    function __construct() {
        parent::__construct();
        $this->load->model('mod_kieuthietbi');
		$this->load->model('mod_bothietbi');
    }
    
    function index(){
        $this->parser->parse('devicetype/list');
    }
    function ajax_list() {
        $list = $this->mod_kieuthietbi->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $kieu) {
            $no++;
            $row = array();
            $row[] = "<a href='".site_url()."thietbi/devicetype/detail?id=".$kieu->type_id."&timecheck=".$kieu->checking_period."'>".$kieu->type_name."</a>";
            $row[] = $kieu->checking_period;
            if($kieu->checking_daily == 1) {
                $row[] = "Có";
            }
            else {
                $row[] = "Không";
            }
            $row[] = "<button class='btn btn-minier btn-info' onclick=\"edit(".$kieu->type_id.",'".$kieu->type_name."',".$kieu->checking_period.",".$kieu->checking_daily.")\"><i class='ace-icon fa fa-pencil smaller'></i></button>
                      <button class='btn btn-minier btn-danger' onclick=\"del(".$kieu->type_id.")\"><i class='ace-icon fa fa-trash-o bigger-110'></i></button>";
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_kieuthietbi->count_all(),
            "recordsFiltered" => $this->mod_kieuthietbi->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    
    function ajax_add() {
        $item = $this->input->post();
        $this->mod_kieuthietbi->_create(array(
            'type_name' => $item['type_name'],
            'checking_period' => $item['time_check'],
            'checking_daily' => $item['kiemtra'],
            'create_date' => date('Y-m-d H:i:s')
        ));
        echo json_encode(array('err_code' => '100'));
    }
    function update() {
        $item = $this->input->post();
        $this->mod_kieuthietbi->_update($item['typeid'], array( 'type_name' => $item['type_name'],
            													'checking_period' => $item['time_check'],
            													'checking_daily' => $item['kiemtra'],
                                                            	'update_date' => date('Y-m-d H:i:s')));
        echo json_encode(array('err_code' => '100'));
    }
    function detail() {
        $this->parser->assign('id', $this->input->get('id'));
        $this->parser->assign('type', $this->input->get('timecheck'));
        $this->parser->parse('devicetype/detail');
    }
  
    function delete() {
        $this->mod_kieuthietbi->_update($this->input->post('id'), array('status' => '2'));
        echo json_encode(array('err_code' => '100'));
    }
}