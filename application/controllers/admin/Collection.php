<?php

require_once APPPATH . '/core/Base_Controller.php';

class Collection extends Base_Controller {
	
public function __construct() {
		parent::__construct();
		$this->load->model("admin_model");
        $this->load->model("collection_model");
        $admin = $this->session->userdata('admin');
		if ($admin == null) {
			redirect(base_url('admin/login'));
		}
		$lockdata = $this->session->userdata('lockdata');
		if ($lockdata != null) {
			redirect(base_url('admin/lockscreen'));
		}
		
		$this->account = $this->admin_model->getAdminAccountByEmail($admin['email']);
        $this->load->helper(array('form'));
	}
	
	public function index() {
		$collections = $this->collection_model->getCollections();
		$content = $this->load->view('admin/collection_list', array('collections' => $collections), true);
		$data = array();
		$data['parent_id'] = 4;
		$data['sub_id'] = 41;
		$data['account'] = $this->account;
		$data['content'] = $content;
		$this->load->view('admin_main_layout', $data);
	}
	
	public function add(){
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $params = array();
            $params['name'] = $this->input->post('shop_name');
            $params['short_bio'] = $this->input->post('short_bio');
            $params['priority'] = $this->collection_model->getMax() + 1;
            $params['created'] = time();
            $this->collection_model->insert($params);
            redirect(base_url('admin/collection'));
        }

        $data = array();
        $data['parent_id'] = 4;
        $data['sub_id'] = 41;
        $data['account'] = $this->account;
        $data['content'] = $this->load->view('admin/collection_add', array(), true);
        $this->load->view('admin_main_layout', $data);
    }

    public function edit($collection_id){
        $collection = $this->collection_model->getCollectionById($collection_id);
        if ($collection == null) {
            redirect('admin/collection');
        }
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $params = array();
            $params['name'] = $this->input->post('name');
            $params['short_bio'] = $this->input->post('short_bio');
            $this->collection_model->update($params, $collection_id);
            redirect(base_url('admin/collection'));
        }
        $data = array();
        $data['parent_id'] = 4;
        $data['sub_id'] = 41;
        $data['account'] = $this->account;
        $data['content'] = $this->load->view('admin/collection_edit', $collection, true);;
        $this->load->view('admin_main_layout', $data);
    }

    public function films($collection_id){
        $collection = $this->collection_model->getCollectionById($collection_id);
        if ($collection == null) {
            redirect('admin/collection');
        }

        $this->load->model("product_model");
        $collection['products'] = $this->product_model->getListProductByCollection($collection_id);
        $collection['others'] = $this->collection_model->getProductOthers($collection_id);
        $collection['max'] = $this->collection_model->getMaxFilm($collection_id);
        $data = array();
        $data['parent_id'] = 4;
        $data['sub_id'] = 41;
        $data['account'] = $this->account;
        $data['content'] = $this->load->view('admin/collection_films', $collection, true);;
        $this->load->view('admin_main_layout', $data);
    }

    public function addFilm($product_id, $collection_id){
        $collection = $this->collection_model->getCollectionById($collection_id);
        if ($collection == null) {
            redirect('admin/collection');
        }
        $params = array();
        $params['collection_id'] = $collection_id;
        $params['product_id'] = $product_id;
        $params['priority'] = $this->collection_model->getMaxFilm($collection_id) + 1;
        $this->collection_model->addFilm($params);
        redirect(base_url('admin/collection/films/'.$collection_id));
    }

    public function removeFilm($collection_id, $product_id, $priority){
        $collection = $this->collection_model->getCollectionById($collection_id);
        if ($collection == null) {
            redirect('admin/collection');
        }
        $this->collection_model->removeFilm($collection_id, $product_id, $priority);
        redirect(base_url('admin/collection/films/'.$collection_id));
    }

    public function upFilm($collection_id, $priority, $id){
        $collection = $this->collection_model->getCollectionById($collection_id);
        if ($collection == null) {
            redirect('admin/collection');
        }
        $this->collection_model->upFilm($collection_id, $priority, $id);
        redirect(base_url('admin/collection/films/'.$collection_id));
    }

    public function downFilm($collection_id, $priority, $id){
        $collection = $this->collection_model->getCollectionById($collection_id);
        if ($collection == null) {
            redirect('admin/collection');
        }
        $this->collection_model->downFilm($collection_id, $priority, $id);
        redirect(base_url('admin/collection/films/'.$collection_id));
    }
}