<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/core/BR_Controller.php';

class News extends BR_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('news_model');
	}

	public function get_get() {
		$this->validate_authorization();
		$news = array();
		$following = array();
		$you = array();
		$items = $this->news_model->getNewForFollowing($this->user_id);
		if (is_array($items) && count($items) > 0) {
			foreach ($items as $item) {
				array_push($following, $this->fillData($item, 1));	
			}
		}
		$items = $this->news_model->getNewForYou($this->user_id);
		if (is_array($items) && count($items) > 0) {
			foreach ($items as $item) {
				array_push($you, $this->fillData($item, 2));	
			}
		}
		$news['following'] = $following;
		$news['you'] = $you;
		$news['num_news'] = $this->news_model->countNotification($this->user_id);
		$this->create_success(array('news' => $news));
	}

	public function getBadge_get(){
		$this->validate_authorization();
		$news['num_news'] = $this->news_model->countNotification($this->user_id);
		$this->create_success($news, 'Update success');
	}

	public function readNotify_post() {
		$notify_id = $this->c_getNumberNotNull('notify_id');

		$this->news_model->update(array('status' => 0), $notify_id);
		$this->create_success(null, 'Update success');
	}
	
	public function readAllNotifies_post() {
		$this->news_model->updateAll($this->user_id, array('status' => 0));
		$news['num_news'] = $this->news_model->countNotification($this->user_id);
		$this->create_success($news, 'Update success');
	}
	
	public function fillData($item, $checkFill){
	
		$notify = array();
		$notify['notify_id'] = $item['notify_id'];
		$notify['type'] = $item['type'];
		$notify['data'] = $item['data'] == null ? null : json_decode($item['data'], true);
		$notify['user_name'] = '';
		$notify['product_name'] = '';
		$notify['avatar'] = '';

		if ($notify['data'] != null) {
			if(isset($notify['data']['user_id'])){
				$user = $this->news_model->getUserForNotify($notify['data']['user_id']);
				$notify['avatar'] = $user['avatar'];
				$notify['user_name'] = $user['user_name'];
			}
			if(isset($notify['data']['uid_comment'])){
				if($notify['data']['uid_comment'] == $notify['data']['user_id']){
					if($notify['type'] == 10){
						$item['content'] = str_replace("<<username>>", 'their', $item['content']);
					}else{
						$item['content'] = str_replace("<<username>>", 'to their', $item['content']);
					}
					
				}else{
					$user = $this->news_model->getUserForNotify($notify['data']['uid_comment']);
					$notify['user_name'] .= '*'.$user['user_name'];
					$item['content'] = str_replace(" <<username>> ", '*', $item['content']);
				}
			}
			if($checkFill == 1){
				$content = '';
				$notify['product_name'] = '';
				if(isset($notify['data']['product_id'])){
					$product = $this->news_model->getProductForNotify($notify['data']['product_id']);
					$notify['product_name'] = $product['name'];
				}
				// if(isset($notify['data']['season_id'])){
				// 	$season = $this->news_model->getSeasonForNotify($notify['data']['season_id']);
				// 	$content = $content.' '.$season['name'];
				// }
				if(isset($notify['data']['episode_id'])){
					$episode = $this->news_model->getPartEpisodeForNotify($notify['data']['episode_id']);
					$content = $content.'part '.$episode['position'];
				}
				if($content != ''){
					$item['content'] = $item['content'].' '.$content.' of';
				}
			}else{
				$content = '';
				// if(isset($notify['data']['season_id'])){
				// 	$season = $this->news_model->getSeasonForNotify($notify['data']['season_id']);
				// 	$content = $content.' '.$season['name'];
				// }
				if(isset($notify['data']['episode_id'])){
					$episode = $this->news_model->getPartEpisodeForNotify($notify['data']['episode_id']);
					$content = $content.'part '.$episode['position'];
				}
				if(isset($notify['data']['product_id'])){
					$product = $this->news_model->getProductForNotify($notify['data']['product_id']);
				}
				if($content != ''){
					$notify['product_name'] = $content.' of '.$product['name'];
				}
			}
		}
		$notify['content'] = $item['content'];
		$notify['timestamp'] = $item['timestamp'];
		$notify['status'] = $item['status'];
		return $notify;
	}
}