<?php

require_once APPPATH . '/core/Base_Controller.php';

class Music extends Base_Controller {

	public function __construct() {
		parent::__construct();
		$this->verifyAdmin();


		$this->load->model("music_model");
	}

	public function index() {
		$musics = $this->music_model->getMusicForAdmin();
		$content = $this->load->view('admin/music_list', array('musics' => $musics), true);
		$data = array();
		$data['parent_id'] = 6;
		$data['sub_id'] = 61;
		$data['account'] = $this->account;
		$data['content'] = $content;
		$data['customJs'] = array('assets/plugins/sweetalert/dist/sweetalert.min.js', 'assets/app/delete-confirm.js');
		$data['customCss'] = array('assets/plugins/sweetalert/dist/sweetalert.css');
		$this->load->view('admin_main_layout', $data);
	}

	public function add() {
		if ($this->addMusic(0)) {
			$this->session->set_flashdata('msg', 'Add success!');
			redirect(base_url('music'));
		}
		$this->load->model("product_model");
		$products = $this->product_model->getProductListForAdmin();
		$data = array();
		$data['parent_id'] = 6;
		$data['sub_id'] = 61;
		$data['account'] = $this->account;
		$data['content'] = $this->load->view('admin/music_add', array('products' => $products), true);
		$this->load->view('admin_main_layout', $data);
	}

	public function edit($music_id) {
		$music = $this->music_model->getMusicById($music_id);
		if ($music == null) {
			redirect('music');
		}
		if ($this->addMusic($music_id)) {
			$this->session->set_flashdata('msg', 'Edit success!');
			redirect(base_url('music'));
		}
		$this->load->model("product_model");
		$products = $this->product_model->getProductListForAdmin();
		$music['products'] = $products;

		$data = array();
		$data['parent_id'] = 6;
		$data['sub_id'] = 61;
		$data['account'] = $this->account;
		$data['content'] = $this->load->view('admin/music_edit', $music, true);
		$this->load->view('admin_main_layout', $data);
	}

	public function delete($music_id) {
		$music = $this->music_model->getMusicById($music_id);
		if ($music == null) {
			redirect('music');
		}
		$this->music_model->delete($music_id);
		$this->session->set_flashdata('msg', 'Delete success!');
		redirect('music');
	}
}