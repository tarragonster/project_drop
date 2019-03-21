<?php defined('BASEPATH') or exit('No direct script access allowed');

class Base_Controller extends CI_Controller {
	protected $account;
	public function __construct() {
		parent::__construct();
		date_default_timezone_set('America/Los_Angeles');
		$this->load->model("admin_model");
		$this->load->helper(array('form'));
	}

	public function redirect($default = '') {
		$redirect = $this->input->get('redirect');
		if ($redirect != '') {
			redirect(urldecode($redirect));
		} else {
			if ($default != '') {
				redirect($default);
			} else {
				redirect('');
			}
		}
	}

	protected function verifyAdmin() {
		$admin = $this->session->userdata('admin');
		if ($admin == null) {
			redirect(base_url('login'));
		}
		$lockdata = $this->session->userdata('lockdata');
		if ($lockdata != null) {
			redirect(base_url('lockscreen'));
		}

		$this->account = $this->admin_model->getAdminAccountByEmail($admin['email']);
	}

	public function addMusic($type) {
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$params = array();
			if ($this->input->post('name') != '') {
				$params['name'] = $this->input->post('name');
			}
			if ($this->input->post('singer') != '') {
				$params['singer'] = $this->input->post('singer');
			}
			if ($this->input->post('product_id') != '') {
				$params['product_id'] = $this->input->post('product_id');
			}
			$url = isset($_FILES['music_url']) ? $_FILES['music_url'] : null;
			$this->load->model('file_model');
			if ($url != null && $url['error'] == 0) {
				$path = $this->file_model->uploadCustom('music_url', 'media/musics/');
				if ($path != null) {
					$params['url'] = $path;
				}
			}
			if ($type == 0) {
				$id = $this->music_model->insert($params);
			} else {
				$id = $this->music_model->update($params, $type);
			}
			if ($id > 0) {
				return true;
			}
			return false;
		}
		return false;
	}

	public function addCast($type) {
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$params = array();
			$params['name'] = $this->input->post('name');
			$params['country'] = $this->input->post('country');
			$params['description'] = $this->input->post('description');
			if ($this->input->post('link_imdb') != '') {
				$params['link_imdb'] = $this->input->post('link_imdb');
			}
			$params['facebook_link'] = $this->input->post('facebook_link');
//			if ($this->input->post('facebook') != '')
			$params['facebook'] = $this->input->post('facebook');
			$params['twitter'] = $this->input->post('twitter');
			$params['instagram'] = $this->input->post('instagram');
//			if ($this->input->post('twitter') != '') {
//				$twitter = explode('/', trim(trim($this->input->post('twitter')), "/"));
//				$params['twitter'] = explode('?', $twitter[3])[0];
//			}
//			if ($this->input->post('instagram') != '') {
//				$instagram = explode('/', trim(trim($this->input->post('instagram')), "/"));
//				$params['instagram'] = explode('?', $instagram[3])[0];
//			}
			if ($this->input->post('product_id') != '') {
				$product_id = $this->input->post('product_id');
			}
			$image = isset($_FILES['image']) ? $_FILES['image'] : null;
			$this->load->model('file_model');
			if ($image != null && $image['error'] == 0) {
				$path = $this->file_model->createFileName($image, 'media/actors/', 'actor');
				$this->file_model->saveFile($image, $path);
				$params['image'] = $path;
			}
			if ($type == 0) {
				$this->db->trans_start();
				$id = $this->cast_model->insert($params);
				if (isset($product_id)) {
					$this->db->insert('film_cast', array('product_id' => $product_id, 'cast_id' => $id));
				}
				$this->db->trans_complete();
			} else {
				$id = $this->cast_model->update($params, $type);
			}
			if ($id > 0) {
				return true;
			}
			return false;
		}
		return false;
	}

	protected function handle_post($redirect = 'admin', $isExport = false) {
		$search = array();
		$post = $this->input->post(NULL, TRUE);
		foreach ($post as $key => $value) {
			if ($key != 'search' && $key != 'export') {
				if ($value != "") {
					$search[] = $key . '=' . urlencode($value);
				}
			}
		}
		if ($isExport) {
			return $search;
		}
		$query = "";
		if (count($search) > 0) {
			$query = "?" . implode('&', $search);
		}
		redirect(base_url($redirect) . $query);
	}
}