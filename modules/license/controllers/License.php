<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class License extends MY_Controller {
    function __construct() {
        parent::__construct();
    }
    function index() {
        switch ($this->input->get('code')) {
            case '102':
                $this->parser->assign('message', 'Bạn <span style="color:#ff0000;">bị tạm ngưng sử dụng</span> hệ thống này');
                $this->parser->assign('contact', 'vui lòng liên hệ <a href="mailto:info@cenlab.vn" style="text-decoration:none;"> administrator </a> để biết thêm chi tiết!');
                break;
            case '103':
                $this->parser->assign('message', 'Bạn <span style="color:#ff0000;">bị từ chối cấp phép sử dụng</span> hệ thống này');
                $this->parser->assign('contact', 'vui lòng liên hệ <a href="mailto:info@cenlab.vn" style="text-decoration:none;"> administrator </a> để biết thêm chi tiết!');
                break;
            case '400':
                $this->parser->assign('message', '<span style="color:#ff0000;">License của bạn đã hết hạn</span>');
                $this->parser->assign('contact', 'vui lòng thử <a href="'.site_url().'" style="text-decoration:none;">tạo lại</a> license!');
                break;
            case '401':
                $this->parser->assign('message', '<span style="color:#ff0000;">Domain của bạn không hợp lệ</span>');
                $this->parser->assign('contact', 'vui lòng liên hệ <a href="mailto:info@cenlab.vn" style="text-decoration:none;"> administrator </a> để biết thêm chi tiết!');
                break;
            case '402':
                $this->parser->assign('message', '<span style="color:#ff0000;">License của bạn không hợp lệ</span>');
                $this->parser->assign('contact', 'vui lòng liên hệ <a href="mailto:info@cenlab.vn" style="text-decoration:none;"> administrator </a> để biết thêm chi tiết!');
                break;
            case '404':
                $this->parser->assign('message', 'Bạn <span style="color:#ff0000;">cần có internet để active</span> hệ thống này');
                $this->parser->assign('contact', 'vui lòng liên hệ <a href="mailto:info@cenlab.vn" style="text-decoration:none;"> administrator </a> để biết thêm chi tiết!');
                break;
            default:
                $this->parser->assign('message', 'Bạn <span style="color:#ff0000;">chưa được cấp phép sử dụng</span> hệ thống này');
                $this->parser->assign('contact', 'vui lòng liên hệ <a href="mailto:info@cenlab.vn" style="text-decoration:none;"> administrator </a> để biết thêm chi tiết!');
                break;
        }
        $this->parser->parse('license');
    }
	function clearLicense() {
            $this->session->sess_destroy();
	}
	function resetLicense() {
        if (file_exists("logs/{$this->_host[0]}_license.json")) unlink("logs/{$this->_host[0]}_license.json");
            $this->session->sess_destroy();
	}
}
