<?php

require_once APPPATH . '/core/Base_Controller.php';

class Episode extends Base_Controller {
	public function __construct() {
		parent::__construct();
		$this->verifyAdmin();


		$this->load->model("episode_model");
	}

	public function index($page = 1) {
		$this->load->library('pagination');
    	$config['base_url'] = base_url('product');
    	$config['total_rows'] = $this->product_model->countAll();
    	$config['per_page'] = PERPAGE_ADMIN;
    	$config['cur_page'] = $page;
    	$config['use_page_numbers'] = TRUE;
    	$config['add_query_string'] = TRUE;
    	$this->pagination->initialize($config);
    	
    	$pinfo = array(
    			'from' 	=> PERPAGE_ADMIN * ($page - 1) + 1 ,
    			'to' 	=> min(array(PERPAGE_ADMIN * $page, $config['total_rows'])) ,
    			'total' => $config['total_rows'],
    	);

		$products = $this->product_model->getProductListForAdmin($page - 1);
		$data['parent_id'] = 3;
		$data['sub_id'] = 32;
		$data['account'] = $this->account;
		$data['content'] = $this->load->view('admin/product_list', array(
			'products' => $products,
			'pinfo' => $pinfo), true);
		$this->load->view('admin_main_layout', $data);
	}

	public function add($season_id) {
        $this->load->model("season_model");
        $season = $this->season_model->getSeasonDetail($season_id);
        if ($season == null) {
            redirect('season');
        }
		$cmd = $this->input->post('cmd');
		if ($cmd != '') {
			$params = array();
			$params['name'] = $this->input->post('name');
            $params['description'] = $this->input->post('description');
            $params['created'] = time();
            $params['season_id'] = $season_id;
            $jw_media_id = trim($this->input->post('jw_media_id'));

			$this->load->library('jw_lib');
			$video = $this->jw_lib->getVideo($jw_media_id);
			if ($video != null) {
				$params['total_time'] = $video['duration'];
			}

            $params['jw_media_id'] = $jw_media_id;
            $params['position'] = $this->episode_model->getPosition($season_id) + 1;
            $image = isset($_FILES['image']) ? $_FILES['image'] : null;
			$this->load->model('file_model');
			if ($image != null && $image['error'] == 0) {				
				$path = $this->file_model->createFileName($image, 'media/episodes/', 'episode');
				$this->file_model->saveFile($image, $path);
				$params['image'] = $path;
			}
			$episode_url = isset($_FILES['url']) ? $_FILES['url'] : null;
            if ($episode_url != null && $episode_url['error'] == 0) {               
                $path = $this->file_model->uploadCustom('url', 'media/videos/episodes/');
                if($path != null){
                    $params['url'] = $path;
                }
            }

            $episode_id = $this->episode_model->insert($params);
            if ($cmd == 'Save') {
            	$this->session->set_flashdata('msg', 'Add success!');
				redirect(base_url('episode/add/'.$season_id));
			}
        }
		$data['parent_id'] = 7;
		$data['sub_id'] = 71;
		$data['account'] = $this->account;
		$data['content'] = $this->load->view('admin/episode_add', $season, true);
		$data['customJs'] = array('assets/js/settings.js', 'assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js', 'assets/app/length.js', 'assets/app/info_video.js');
		$data['customCss'] = array('assets/css/settings.css' ,'assets/css/smoothness.jquery-ui.css');
		$this->load->view('admin_main_layout', $data);
	}

	public function edit($episode_id, $season_id){
        $episode = $this->episode_model->getEpisodeDetail($episode_id);
        if ($episode == null) {
            redirect('season/episode/'.$season_id);
        }
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $params = array();
            if($this->input->post('name') != '')
                $params['name'] = $this->input->post('name');
            if($this->input->post('description') != '')
                $params['description'] = $this->input->post('description');

	        $jw_media_id = trim($this->input->post('jw_media_id'));
			if (!empty($jw_media_id) && $jw_media_id != $episode['jw_media_id']) {
				$this->load->library('jw_lib');
				$video = $this->jw_lib->getVideo($jw_media_id);
				if ($video != null) {
					$params['total_time'] = $video['duration'];
				}

				$params['jw_media_id'] = $jw_media_id;
			}

            $image = isset($_FILES['image']) ? $_FILES['image'] : null;
			$this->load->model('file_model');
			if ($image != null && $image['error'] == 0) {				
				$path = $this->file_model->createFileName($image, 'media/episodes/', 'episode');
				$this->file_model->saveFile($image, $path);
				$params['image'] = $path;
			}
            $episode_url = isset($_FILES['url']) ? $_FILES['url'] : null;
            if ($episode_url != null && $episode_url['error'] == 0) {               
                $path = $this->file_model->uploadCustom('url', 'media/videos/episodes/');
                if($path != null){
                    $params['url'] = $path;
                }
            }
            $this->episode_model->update($params, $episode_id);
            
            $this->session->set_flashdata('msg', 'Edit success!');
            redirect(base_url('episode/edit/'.$episode_id.'/'.$season_id));
        }
        $data = array();
        $data['parent_id'] = 7;
        $data['sub_id'] = 71;
        $data['account'] = $this->account;
        $data['content'] = $this->load->view('admin/episode_edit', $episode, true);;
        $data['customCss'] = array('assets/css/settings.css' ,'assets/css/smoothness.jquery-ui.css');
        $data['customJs'] = array('assets/js/settings.js', 'assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js', 'assets/app/length.js', 'assets/app/info_video.js');
        $this->load->view('admin_main_layout', $data);
    }

    public function delete($episode_id, $season_id){
        $episode = $this->episode_model->getEpisodeDetail($episode_id);
        if ($episode == null) {
            redirect('season/episode/'.$season_id);
        }
        $this->db->trans_start();
        $this->episode_model->updatePosition($season_id, $episode['position']);
        $this->episode_model->delete($episode_id);

	    $this->load->model('notify_model');
	    $this->notify_model->deleteReference('episode', $episode_id);
        $this->db->trans_complete();
        redirect('season/episode/'.$season_id);
    }
    public function up($episode_id, $season_id){
        $episode = $this->episode_model->getEpisodeDetail($episode_id);
        if ($episode == null) {
            redirect('season/episode/'.$season_id);
        }
        $this->episode_model->up($season_id, $episode['position'], $episode_id);
        redirect('season/episode/'.$season_id);
    }
    public function down($episode_id, $season_id){
        $episode = $this->episode_model->getEpisodeDetail($episode_id);
        if ($episode == null) {
            redirect('season/episode/'.$season_id);
        }
        $this->episode_model->down($season_id, $episode['position'], $episode_id);
        redirect('season/episode/'.$season_id);
    }
}
