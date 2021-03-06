﻿<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cate extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct()
    {
    	parent::__construct();
    	$this->load->helper('url');
    	//$this->load->library('pagination');
		$this->cii_pagination = new cii_pagination();
		$this->load->model('cate_model');
    }
    
	public function index($cate_url = '', $pn = 1)
	{
		switch( $cate_url ){
			case 'yuwen':
				$cate_id = 5;
				$cate_title = '语文';
				break;
			case 'shuxue':
				$cate_id = 6;
				$cate_title = '数学';
				break;
			case 'waiyu':
				$cate_id = 7;
				$cate_title = '外语';
				break;
			case 'jisuanji':
				$cate_id = 8;
				$cate_title = '计算机';
				break;
			case 'dili':
				$cate_id = 1;
				$cate_title = '地理';
				break;
			case 'lishi':
				$cate_id = 2;
				$cate_title = '历史';
				break;
			case 'zhengzhi':
				$cate_id = 3;
				$cate_title = '政治';
				break;
			case 'wenzong':
				$cate_id = 4;
				$cate_title = '文综';
				break;
			case 'shenlun':
				$cate_id = 9;
				$cate_title = '申论精品文章';
				break;
			default:
				redirect(base_url('error'.'.html'));
		}
		// 每页显示数量
		$limit = 10;
		// 总记录数量
		$total = $this->cate_model->get_count($cate_id);
		// 总页数
		$page = ceil($total/$limit);
		/*
		 * 容错处理
		 * 1.如果页数不是数字
		 * 2.如果页数不是整数
		 * 3.如果页数小于首页
		 * 4.如果页数大于尾页
		 * 转到404
		*/
		if( (!empty($pn) && !is_numeric($pn)) || ($pn > intval($pn)) || ($pn < 1) || ($page && $pn > $page) ){
			redirect(base_url('error'.'.html'));
		}
		// 计算偏移量
		$offset = ($pn - 1) * $limit;
		//
		$data['cate']['title'] = $cate_title;
		$data['cate']['pn'] = $pn;
		$data['cate']['doc'] = $this->cate_model->get_list($cate_id, $limit, $offset);
		foreach($data['cate']['doc'] as $key => $value){
			$data['cate']['doc'][$key]['doc_desc'] = mb_substr(strip_tags($value['doc_desc']), 0, 250);
		}
		//
		$data['cate']['hot'] = $this->cate_model->get_hot($cate_id);
		$data['cate']['rand'] = $this->cate_model->get_rand($cate_id);
		//
/*
		$config['pagination']['base_url'] = base_url('cate/'.$cate_url.'/');
		$config['pagination']['full_tag_open'] = '<div class="pn"><div class="pn-container">';
		$config['pagination']['full_tag_close'] = '<div class="clearfix"></div></div><div class="clearfix"></div></div>';
		$config['pagination']['first_tag_open'] = '<span>';
		$config['pagination']['first_tag_close'] = '</span>';
		$config['pagination']['last_tag_open'] = '<span>';
		$config['pagination']['last_tag_close'] = '</span>';
		$config['pagination']['next_tag_open'] = '<span>';
		$config['pagination']['next_tag_close'] = '</span>';
		$config['pagination']['prev_tag_open'] = '<span>';
		$config['pagination']['prev_tag_close'] = '</span>';
		$config['pagination']['cur_tag_open'] = '<span class="current">';
		$config['pagination']['cur_tag_close'] = '</span>';
		$config['pagination']['num_tag_open'] = '<span>';
		$config['pagination']['num_tag_close'] = '</span>';
		$config['pagination']['use_page_numbers'] = true;
		$config['pagination']['first_link'] = '首页';
		$config['pagination']['prev_link'] = '上一页';
		$config['pagination']['next_link'] = '下一页';
		$config['pagination']['last_link'] = '尾页';
		$config['pagination']['uri_segment'] = 3;
		$config['pagination']['total_rows'] = $total;
		$config['pagination']['per_page'] = $limit;
		$this->pagination->initialize($config['pagination']);
*/
		$cii_pagination['base_url'] = base_url('cate/'.$cate_url.'/');
		$cii_pagination['per_page'] = $limit;
		$cii_pagination['total_rows'] = $total;
		$cii_pagination['first_link'] = '首页';
		$cii_pagination['prev_link'] = '上一页';
		$cii_pagination['next_link'] = '下一页';
		$cii_pagination['last_link'] = '尾页';
		$this->cii_pagination->initialize($cii_pagination);
		//
		$this->load->view('cate_view', $data);
	}
}
