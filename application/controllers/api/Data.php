<?php
/**
 * Created by PhpStorm.
 * User: ricky
 * Date: 7/4/19
 * Time: 1:16 PM
 */
require APPPATH . '/core/BR_Controller.php';

class Data extends BR_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('user_model');
	}

	/**
	 * @SWG\Get(
	 *     path="/data",
	 *     summary="Get config data",
	 *     operationId="get-data",
	 *     tags={"Authorization"},
	 *     produces={"application/json"},
	 *     consumes={"application/json"},
	 *     @SWG\Response(
	 *         response=200,
	 *         description="Successful operation",
	 *     ),
	 *     security={
	 *       {"accessToken": {}}
	 *     }
	 * )
	 */
	public function index_get() {
		$response = [];
		$this->load->model('genre_model');
		$response['genres'] = $this->genre_model->getGenresList();
		$this->create_success($response);
	}
}