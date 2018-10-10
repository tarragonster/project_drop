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
			$this->load->model('episode_model');
			foreach ($collections as &$collection) {
				if ($collection['collection_type'] == 3) {
					$episode_products = $this->product_model->getContinueWatching($this->user_id);
					if (is_array($episode_products)) {
						foreach ($episode_products as &$product) {
							$episode = $this->product_model->getEpisode($product['episode_id']);
							$episode['product_id'] = $product['product_id'];
							$episode['product_name'] = $product['name'];
							$episode['start_time'] = $product['start_time'];
							$product['episode'] = $episode;
						}
					}
					$collection['products'] = $episode_products;
				} else {
					if ($collection['collection_type'] == 4) {
						$top_picks = $this->user_model->getTopPicks();
						$collection['products'] = $top_picks;
					} else {
						$products = $this->product_model->getListProductByCollection($collection['collection_id'], 0);
						if ($this->user_id == null) {
							foreach ($products as $key => $row) {
								$products[$key]['start_time'] = 0;
								$first_episode = $this->product_model->getFirstEpisode($row['product_id']);
								$products[$key]['episode'] = $first_episode;
							}
						} else {
							foreach ($products as $key => $row) {
								$products[$key]['start_time'] = $this->product_model->getProductContinue($this->user_id, $row['product_id']);
								$first_episode = $this->product_model->getFirstEpisode($row['product_id']);
								$products[$key]['episode'] = $first_episode;
							}
						}
						$collection['products'] = $products;
					}
				}
			}
		}

		$this->create_success(array('collections' => $collections));
	}
}