<?php

require_once APPPATH . '/core/Base_Controller.php';

class Feed extends Base_Controller {
	
public function __construct() {
		parent::__construct();
		$this->load->model("admin_model");
        $this->load->model("feed_model");
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
		$feeds = $this->feed_model->getFeeds();
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $params = array();
            $params['product_id'] = $this->input->post('product_id') * 1;
            $this->load->model("product_model");
            $product = $this->product_model->getProductForAdmin($params['product_id']);
            if ($product == null) {
                redirect('admin/feed');
            }

            $image = isset($_FILES['image']) ? $_FILES['image'] : null;
            if ($image != null && $image['error'] == 0) {              
                $this->load->model('file_model'); 
                $path = $this->file_model->createFileName($image, 'media/feeds/', 'feed');
                $this->file_model->saveFile($image, $path);
                $params['feed_image'] = $path;
                $params['timestamp'] = time();
                $params['status'] = 1;
                $params['position'] = $this->feed_model->getMax() + 1;
                $this->feed_model->insert($params);
            }else{
                $this->session->set_flashdata('msg', 'Missing data field required: image');
            }
            $this->redirect('admin/feed');
        }
		$content = $this->load->view('admin/feed_list', array('feeds' => $feeds, 'max' => $this->feed_model->getMax()), true);
		$data = array();
		$data['parent_id'] = 8;
		$data['sub_id'] = 81;
		$data['account'] = $this->account;
		$data['content'] = $content;

        $data['customCss'] = array('assets/css/jquery-ui.css', 'assets/css/settings.css' ,'assets/css/smoothness.jquery-ui.css');
        $data['customJs'] = array('assets/js/jquery-ui.js', 'assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js', 'assets/app/length.js', 'assets/app/feed_autocomplete.js');
		$this->load->view('admin_main_layout', $data);
	}
	
	public function active($feed_id) {
        $feed = $this->feed_model->getFeedForAdmin($feed_id);
        if($feed == null){
            redirect('admin/feed');
        }
        $this->feed_model->update(array('status' => 1 - $feed['status'], 'timestamp' => time()), $feed_id);
        redirect('admin/feed');
    }

    public function edit($feed_id) {
        $feed = $this->feed_model->getFeedForAdmin($feed_id);
        if($feed == null){
            redirect('admin/feed');
        }
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $params = array();
            $product_id = $this->input->post('product_id') * 1;
            if($product_id != 0){
                $params['product_id'] = $product_id;
                $this->load->model("product_model");
                $product = $this->product_model->getProductForAdmin($params['product_id']);
                if ($product == null) {
                    redirect('admin/feed');
                }
            }

            $image = isset($_FILES['image']) ? $_FILES['image'] : null;
            if ($image != null && $image['error'] == 0) {              
                $this->load->model('file_model'); 
                $path = $this->file_model->createFileName($image, 'media/feeds/', 'feed');
                $this->file_model->saveFile($image, $path);
                $params['feed_image'] = $path;
            }
            $this->feed_model->update($params, $feed_id);
            $this->redirect('admin/feed');
        }
        $content = $this->load->view('admin/feed_edit', array('feed' => $feed), true);
        $data = array();
        $data['parent_id'] = 8;
        $data['sub_id'] = 81;
        $data['account'] = $this->account;
        $data['content'] = $content;

        $data['customCss'] = array('assets/css/jquery-ui.css', 'assets/css/settings.css' ,'assets/css/smoothness.jquery-ui.css');
        $data['customJs'] = array('assets/js/jquery-ui.js', 'assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js', 'assets/app/length.js', 'assets/app/feed_autocomplete.js');
        $this->load->view('admin_main_layout', $data);
    }
    

    public function up($feed_id) {
        $item = $this->feed_model->getFeedById($feed_id);
        if ($item == null)
            $this->redirect('admin/feed');
        if ($item['position'] > 1)
            $this->feed_model->up($item);
            
        $this->redirect('admin/feed');
    }
    
    public function down($feed_id) {
        $item = $this->feed_model->getFeedById($feed_id);
        if ($item == null)
            $this->redirect('admin/feed');
        if ($item['position'] < $this->feed_model->getMax())
            $this->feed_model->down($item);
        $this->redirect('admin/feed');
    }
    
    public function remove($feed_id) {
        $item = $this->feed_model->getFeedById($feed_id);
        if ($item == null)
            $this->redirect('admin/feed');
        $this->feed_model->remove($item);
        $this->redirect('admin/feed');
    }

    public function ajaxLoadData(){
        $query = isset($_GET["query"]) ? $_GET["query"] : '';
        $others = $this->feed_model->getOthers($query);
        $html = $this->load->view('admin/ajax_feed', array('others' => $others), true);
        die(json_encode($html));
    }

    public function ajaxProduct() {
        $q = $this->input->get('q');
        if (!empty($q))
            $this->db->like('name', $q, 'both');
        $this->db->where('status', 1);
        $this->db->order_by('name', 'asc');
        $this->db->limit(15);

        $query = $this->db->get('product');
        $products = $query->result_array();
        $items = array();
        if (is_array($products)) {
            foreach($products as $row) {
                $item = array('value' => $row['product_id'], 'label' => $row['name'], );
                $items[] = $item;
            }
        }
        header('Content-Type: application/json');
        echo json_encode($items);
    }
}