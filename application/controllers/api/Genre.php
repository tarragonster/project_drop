<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/core/BR_Controller.php';

class Genre extends BR_Controller {
	public function __construct() {
		parent::__construct();

		$this->validate_authorization();
		$this->load->model('genre_model');
	}

	/**
	 * @SWG\Get(
	 *     path="/genre/{genre_id}/films",
	 *     summary="Get Genre films",
	 *     operationId="getGenreFilms",
	 *     tags={"Collection"},
	 *     produces={"application/json"},
	 *     @SWG\Parameter(
	 *         description="Genre ID",
	 *         in="path",
	 *         name="genre_id",
	 *         required=true,
	 *         type="number",
	 *         format="int64"
	 *     ),
	 *     @SWG\Parameter(
	 *         description="Page",
	 *         in="query",
	 *         name="page",
	 *         required=true,
	 *         type="number",
	 *         format="int64",
	 *         default="0"
	 *     ),
	 *     @SWG\Parameter(
	 *         description="Number of items per page.",
	 *         in="query",
	 *         name="limit",
	 *         required=true,
	 *         type="number",
	 *         format="int64",
	 *         default="24"
	 *     ),
	 *     security={
	 *       {"accessToken": {}}
	 *     },
	 *     @SWG\Response(
	 *         response=200,
	 *         description="Successful operation",
	 *     )
	 * )
	 */
	public function films_get($genre_id = 0) {
		$page = $this->get('page') * 1;
		$limit = $this->get('limit') * 1;
		if ($limit <= 0) {
			$limit = 24;
		}

		$this->load->model('genre_model');
		$genre = $this->genre_model->get($genre_id);
		if ($genre == null) {
			$this->create_error(-901);
		}
		if ($genre['status'] != 1) {
			$this->create_error(-86);
		}

		$films = $this->genre_model->getGenreFilms($genre_id, $page, $limit);
		$this->load->model('product_model');
		foreach ($films as $key => $film) {
			$films[$key]['number_like'] = $this->product_model->countProductLikes($film['product_id']);
			$films[$key]['num_watching'] = $this->product_model->countUserWatching($film['product_id']);
			$films[$key]['watchlist_added'] = $this->user_model->checkWatchList($this->user_id, $film['product_id']) ? '1' : '0';
		}
		$response['products'] = $films;
		$this->create_success($response, 'Successfully');
	}

}