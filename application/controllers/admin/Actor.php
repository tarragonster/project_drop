<?php

require_once APPPATH . '/core/Base_Controller.php';
require_once APPPATH . '/libraries/Simple_html_dom.php';
class Actor extends Base_Controller {
	
public function __construct() {
		parent::__construct();
		$this->load->model("admin_model");
        $this->load->model("cast_model");
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
		$actors = $this->cast_model->getCasts();
		$content = $this->load->view('admin/actor_list', array('actors' => $actors), true);
		$data = array();
		$data['parent_id'] = 5;
		$data['sub_id'] = 51;
		$data['account'] = $this->account;
		$data['content'] = $content;
        $data['customJs'] = array('assets/plugins/sweetalert/dist/sweetalert.min.js', 'assets/app/delete-confirm.js', 'assets/app/toogle.js');
        $data['customCss'] = array('assets/plugins/sweetalert/dist/sweetalert.css');
		$this->load->view('admin_main_layout', $data);
	}
	
	public function add(){
        if($this->addCast(0)){
            $this->session->set_flashdata('msg', 'Add success!');
            redirect(base_url('admin/actor'));
        }
        $this->load->model("product_model");
        $products = $this->product_model->getProductListForAdmin();
        $data = array();
        $data['parent_id'] = 5;
        $data['sub_id'] = 51;
        $data['account'] = $this->account;
        $data['content'] = $this->load->view('admin/actor_add', array('products' => $products), true);
        $data['customCss'] = array('assets/css/settings.css' ,'assets/css/smoothness.jquery-ui.css');
        $data['customJs'] = array('assets/js/settings.js', 'assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js', 'assets/app/length.js', 'assets/app/getDescription.js', 'assets/app/face_book.js','assets/app/film_info.js');
        $this->load->view('admin_main_layout', $data);
    }

    public function edit($cast_id){
        $actor = $this->cast_model->getCastById($cast_id);
        $actor['products'] = $this->cast_model->getListFilms($cast_id);
        $actor['others'] = $this->cast_model->getFilmOthers($cast_id);
        if ($actor == null) {
            redirect('admin/actor');
        }
        if($this->addCast($cast_id)){
            $this->session->set_flashdata('msg', 'Edit success!');
            redirect(base_url('admin/actor/edit/'.$cast_id));
        }
        $data = array();
        $data['parent_id'] = 5;
        $data['sub_id'] = 51;
        $data['account'] = $this->account;
        $data['content'] = $this->load->view('admin/actor_edit', $actor, true);;
        $data['customCss'] = array('assets/css/settings.css' ,'assets/css/smoothness.jquery-ui.css');
        $data['customJs'] = array('assets/js/settings.js', 'assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js', 'assets/app/length.js', 'assets/app/getDescription.js', 'assets/app/film_info.js', 'assets/app/face_book.js');
        $this->load->view('admin_main_layout', $data);
    }

    public function delete($cast_id){
        $actor = $this->cast_model->getCastById($cast_id);
        if ($actor == null) {
            $this->session->set_flashdata('error', 'This Actor is not exists!');
            redirect('admin/actor');
        }
        $this->cast_model->deleteCast($cast_id);
        $this->session->set_flashdata('msg', 'Delete success!');
        redirect('admin/actor');
    }

    public function ajaxLoadData(){
        $query = isset($_GET["query"]) ? $_GET["query"] : '';
        $product_id = isset($_GET["product_id"]) ? $_GET["product_id"] : '';
        $others = $this->cast_model->getCastOthers($product_id, $query);
        $html = $this->load->view('admin/ajax_actor', array('others' => $others, 'product_id' => $product_id), true);
        die(json_encode($html));
    }

    public function ajaxLoadActor(){
        $query = isset($_GET["query"]) ? $_GET["query"] : '';
        $html = file_get_html($query);
        $a = $html->find('tr.findResult', 0);
        echo explode('/', $a->find('a', 0)->href)[2];
    }
    public function addProduct($product_id = 0, $cast_id = 0){
        $this->load->model('product_model');
        $cast = $this->cast_model->getCastById($cast_id);
        $product = $this->product_model->getProductForAdmin($product_id);
        if ($product == null || $cast == null) {
            redirect('admin/cast');
        }
        $this->db->insert('film_cast', array('product_id'=> $product_id, 'cast_id' => $cast_id));
        $this->session->set_flashdata('add', 'Add success!');
        redirect(base_url('admin/actor/edit/'.$cast_id));
    }
    public function removeProduct($product_id = 0, $cast_id = 0){
        $this->load->model('product_model');
        $cast = $this->cast_model->getCastById($cast_id);
        $product = $this->product_model->getProductForAdmin($product_id);
        if ($product == null || $cast == null) {
            redirect('admin/cast');
        }
        $this->db->where('product_id', $product_id);
        $this->db->where('cast_id', $cast_id);
        $this->db->delete('film_cast');
        $this->session->set_flashdata('remove', 'Remove success!');
        redirect(base_url('admin/actor/edit/'.$cast_id));
    }
}