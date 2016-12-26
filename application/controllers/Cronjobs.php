<?php
require APPPATH . '/core/Base_Controller.php';

class Cronjobs extends Base_Controller{

	public function __construct(){
		parent::__construct();
	}

	public function push(){
		$this->load->library('cipush');
		$this->cipush->start();
	}

}