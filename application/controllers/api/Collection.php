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
			foreach ($collections as &$collection) {
				$products = $this->product_model->getListProductByCollection($collection['collection_id'], 0);
				$collection['products'] = $products;
				if ($collection['collection_id'] == 4) {
					$episode_products = $this->product_model->getContinueWatching($this->user_id);
					if (is_array($episode_products)) {
						foreach ($episode_products as &$product) {
							$episode = $this->product_model->getEpisode($product['episode_id']);
							$episode['start_time'] = $product['start_time'];
							$product['episode'] = $episode;
						}
					}
					$collection['episode_products'] = $episode_products;
				}
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