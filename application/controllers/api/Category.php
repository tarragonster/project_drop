<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/core/BR_Controller.php';

class Category extends BR_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('category_model');
		$this->load->model('product_model');
		// $this->validate_access_token();
	}

	public function list_post() {
		$page = $this->post('page');
		$categories = $this->category_model->getCategories($page);
		if (is_array($categories)) {
			foreach ($categories as $k => $row) {
				$products = $this->product_model->getListProductByCategory($row['category_id'], 0);
				$categories[$k]['products'] = $products;
			}
		}
		$this->create_success(array('categories' => $categories));
	}

	public function getProducts_post() {
		$category_id = $this->post('category_id');
		$products = $this->product_model->getListProductByCategory($category_id);
		$this->create_success(array('products' => $products));
	}
}