<?php

require_once APPPATH . '/core/Base_Controller.php';

class Season extends Base_Controller {
	
public function __construct() {
		parent::__construct();
		$this->load->model("admin_model");
        $this->load->model("season_model");
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
		$seasons = $this->season_model->getSeasonForAdmin();
        foreach ($seasons as $key => $item) {
            $seasons[$key]['num_episode'] = $this->season_model->getNumEpisode($item['season_id']);
        }
		$content = $this->load->view('admin/season_list', array('seasons' => $seasons), true);
		$data = array();
		$data['parent_id'] = 7;
		$data['sub_id'] = 71;
		$data['account'] = $this->account;
		$data['content'] = $content;
        $data['customJs'] = array('assets/plugins/sweetalert/dist/sweetalert.min.js', 'assets/app/delete-confirm.js');
        $data['customCss'] = array('assets/plugins/sweetalert/dist/sweetalert.css');
		$this->load->view('admin_main_layout', $data);
	}

    public function edit($season_id){
        $season = $this->season_model->getSeasonById($season_id);
        if ($season == null) {
            redirect('admin/season');
        }
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $params = array();
            $params['name'] = $this->input->post('name');
            $params['product_id'] = $this->input->post('product_id');
            $this->season_model->update($params, $season_id);
            redirect(base_url('admin/season'));
        }
        $this->load->model("product_model");
        $products = $this->product_model->getProductListForAdmin();
        $season['products'] = $products;
        $data = array();
        $data['parent_id'] = 7;
        $data['sub_id'] = 71;
        $data['account'] = $this->account;
        $data['content'] = $this->load->view('admin/season_edit', $season, true);;
        $this->load->view('admin_main_layout', $data);
    }


    public function delete($season_id){
        $season = $this->season_model->getSeasonById($season_id);
        if ($season == null) {
            $this->session->set_flashdata('error', 'This Season is not exists!');
            redirect('admin/season');
        }
        $this->season_model->deleteSeason($season_id);
        $this->session->set_flashdata('msg', 'Delete success!');
        redirect('admin/season');
    }

    public function episode($season_id){
        $season = $this->season_model->getSeasonDetail($season_id);
        if ($season == null) {
            redirect('admin/season');
        }
        $episodes = $this->season_model->getListEpisodes($season_id);
        $season['episodes'] = $episodes;
        $this->load->model("episode_model");
        $season['max'] = $this->episode_model->getMaxEpisode($season_id);
        $data = array();
        $data['parent_id'] = 7;
        $data['sub_id'] = 71;
        $data['account'] = $this->account;
        $data['content'] = $this->load->view('admin/episode_list', $season, true);
        $data['customJs'] = array('assets/plugins/sweetalert/dist/sweetalert.min.js', 'assets/app/delete-confirm.js');
        $data['customCss'] = array('assets/plugins/sweetalert/dist/sweetalert.css');
        $this->load->view('admin_main_layout', $data);
    }
}