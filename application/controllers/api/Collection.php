<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/core/BR_Controller.php';

class Collection extends BR_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('collection_model');
		$this->load->model('product_model');
	}

	public function list_get() {
		$collections = $this->collection_model->getCollections();
		if (is_array($collections)) {
			foreach ($collections as $k => $row) {
				$products = $this->product_model->getListProductByCollection($row['collection_id'], 0);
				$collections[$k]['products'] = $products;
			}
		}
		$feeds = $this->collection_model->feeds();
		if (is_array($feeds)) {
			if ($this->user_id == null) {
				foreach ($feeds as $key => $row) {
					$feeds[$key]['start_time'] = 0;
					$first_episode = $this->product_model->getFirstEpisode($row['product_id']);
					$feeds[$key]['feed_episode'] = $first_episode;
				}
			} else {
				foreach ($feeds as $key => $row) {
					$feeds[$key]['start_time'] = $this->product_model->getProductContinue($this->user_id, $row['product_id']);
					$first_episode = $this->product_model->getFirstEpisode($row['product_id']);
					$feeds[$key]['feed_episode'] = $first_episode;
				}
			}
		}
		$this->create_success(array('collections' => $collections, 'feeds' => $feeds));
	}
}