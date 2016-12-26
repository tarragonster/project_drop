<?php

require_once APPPATH . '/libraries/REST_Controller.php';


class BR_Controller extends REST_Controller {
	public $oauths;
	protected $access_token = '';
	protected $user_id;

	public function __construct() {
		parent::__construct();
		date_default_timezone_set('UTC');
		$this->validate_authorization(1);
		$this->db->insert('aa_manager_api', array('api_name' => uri_string(),
				'post' 			=> json_encode($this->post()),
				'image' 		=> json_encode($_FILES),
				'get' 			=> json_encode($this->get()),
				'access_token' 	=> $this->access_token,
				'ctime'			=> time()
		));
	}
	
	protected function create_error($code, $message = '') {
		$response = array();
		if ($message != '')
			$message = ': ' . $message;
		$message = $this->getErrorMessage($code) . $message;
		$response = array('code' => $code,
				'message' => $message,
				'data' => null);
		$this->response($response, 200);
	}

	protected function validate_authorization($temp = -1){
		$authorization = $this->input->get_request_header('Authorization-data');
		$authorization = base64_decode($authorization);
		if ($authorization != false) {
			$arr = explode('|', $authorization);
			if (count($arr) == 2) {
				$this->access_token = $arr[1];
				$this->user_id = $arr[0] * 1;
				$this->load->library('oauths');
				if (!$this->oauths->validate($this->user_id, $this->access_token)) {
					$this->create_error(-1002);
				}
				return true;
			}
		}
		if($temp == -1){
			$this->create_error(-1003);
		}
	}
	
	protected function create_success($data, $message = 'Success') {
		if ($this->input->get_request_header('Devicetype') == 'Android') {
			if ($data == null) {
				$response = array(); 
			} else {
				$response = $data;
			}
			$response['code'] = 0;
		} else {
			$response = array();
			$response['code'] = 0;
			$response['message'] = $message;
			$response['data'] = $data;
		}
	
		$this->response($response, 200);
	}
	
	private function getErrorMessage($code) {
		if (array_key_exists($code, $this->error_array))  {
			return $this->error_array[$code];
		}
		return $this->error_array[-1000];
	}
	
	private $error_array = array(
			1 => 'Warming',
			-1 	=> 'Missing data field required',
			-2 	=> 'System error',
			-3 	=> 'Database error',
			-4 	=> 'Username or password invalid',
			-5 	=> 'Sorry, this email is already linked to an existing account',
			-6 	=> 'Sorry, this username is already in use',
			-7 	=> 'Not change any information',
			-8 	=> 'Field invalid',
			-9 	=> 'This email does not exist in our system.',
			-10	=> 'This uid does not exist in our system.',
			-11	=> 'Can\'t to create conversations',
			-12	=> 'This conversation is not exists',
			-13	=> 'File size to less than 10M and have format image file',
			-14	=> 'Can\'t get data',
			-15	=> 'Product info is wrongging',
			-16	=> 'Can\t insert product',
			-17	=> 'product_id is wrongging',
			-18	=> 'Favorite product when favorited',
			-19	=> 'Unfavorite when not favorite',
			-20	=> 'Can\'t feedback or follow yourself',
			-21	=> 'Missing image or wrong format',
			-22	=> 'Creditcard is exists',
			-23	=> 'Not found creditcard',
			-24	=> 'Access token error or no response from facebook',
			-25	=> 'Not exits zipcode',
			-26	=> 'Can\'t post address',
			-27	=> 'Not exists notify_id',
			-28	=> 'Type of message is wrong',
			-29	=> 'Not exists con_id',
			-30	=> 'con_id and uid not match',
			-31	=> 'The product not contain image_id',
			-32	=> 'Not found this card_id',
			-33	=> 'Miss match list_type and list_index',
			-34 => 'Wrong status of product',
			-35 => 'Film is inactive',
			-36 => 'Not exists order',
			-37 => 'You have report this product',
			-38 => 'You have report this seller',
			-39 => 'mark as sold error',
			-40 => 'conversation mark as sold',
			-41 => 'conversation not contain a product',
			-42 => 'user not in this conversation',
			-43 => 'zipcode in order invalid',
			-44 => 'Address invalid',
			-45 => 'Parcel invalid',
			-46 => 'Can not create shippment',
			-47 => 'Can not register device_id',
			-48 => 'Token invalid',
			-49 => 'Not found address_id',
			-50 => 'Address not match with current user',
			-51 => 'Payment info invalid',
			-52 => 'Can\'t payment with this card',
			-53 => 'Address not match',
			-54 => 'Payment status not ready for success',
			-55 => 'Email not change',
			-56 => 'Size id list wrong',
			-57 => 'The collection not exists',
			-58 => 'The account has linked to other facebook account',
			-59 => 'The account has not linked to any facebook account',
			-60 => 'The account has linked to other twitter account',
			-61 => 'The account has not linked to any twitter account',
			-62 => 'List id is invalid',
			-63 => 'The facebook account has linked to other account',
			-64 => 'The twitter account has linked to other account',
			-65 => 'Wrong brand_id',
			-66 => 'Quantity not accepted',
			-67 => 'Shipping cart is empty',
			-68 => 'Can not add this card',
			-69 => 'Can not add this product to cart',
			-70 => '', // from authorize
			-71 => 'Promotion not found',
			-72 => 'The promo code entered is invalid or is no longer active.', //'Can not apply this promo now',
			-73 => 'The promo code entered is invalid or is no longer active.', // The promotion is expired',
			-74 => 'The promo code entered is invalid or is no longer active.', //The promotion has apply for maxium orders',
			-75 => 'Can not add more than once giveaway product',
			-76 => 'Film added',
			-77 => 'Episode is inactive',
			-78 	=> 'Sorry, this username is already linked to an existing account',
			-79 => 'Followed',
			-1000 	=> 'Undefine error',
			-1001 	=> 'Apikey invalid',
			-1002 	=> 'Your account have beed deactived!',
			-1003	=> "Access denied.",
			-1005 	=> 'Unknow resource',
		);
	
	public function c_getNotNull($key) {
		$value = $this->post($key, '');
		if ($value == '')
			$this->create_error(-1, $key);
		return $value;
	}
	
	public function c_getWithLength($key, $to, $from = 0) {
		$value = $this->post($key, '');
		if (strlen($value) > $to || strlen($value) < $from)
			$this->create_error(-8, $key . ' lenght from ' . $from . ' to ' . $to);
		return $value;
	}
	
	public function c_getNumberNotNull($key) {
		$value = $this->post($key, '');
		if ($value == '')
			$this->create_error(-1, $key);
		if (!validate_number($value))
			$this->create_error(-8, $key . ' must be a number');
		return 1 * $value;
	}
	
	public function c_getNumberBetween($key, $from, $to) {
		$value = $this->post($key, '');
		if ($value == '')
			$this->create_error(-1, $key);
		if (!validate_number($value))
			$this->create_error(-8, $key);
		$value = $value * 1;
		if ($value < $from || $value > $to)
			$this->create_error(-8, $key . ' must from ' . $from . ' to ' . $to);
		return $value;
	}
	
	public function c_getEmail($key) {
		$value = $this->post($key, '');
		if ($value == '')
			$this->create_error(-1, $key);
		if (!validate_email($value))
			$this->create_error(-8, $key);
		return  $value;
	}
	
	public function c_getOnlyContainNumber($key) {
		$value = $this->post($key, '');
		if ($value == '')
			$this->create_error(-1, $key);
		if (!validate_number($value))
			$this->create_error(-8, $key);
		return $value;
	}
	
	public function __checkMentioned($content, $user_id) {
		$usernames = getUsernameCanbe($content);
		$usernames = array_map('strtoupper', $usernames);
		if (count($usernames) > 0) {
			$sql = 'select user_id, user_name from user where upper(user_name) IN ("'.implode('", "', $usernames).'") AND status = 1 AND user_id != '.$user_id;
			$query = $this->db->query($sql);
			if ($query->num_rows() > 0) {
				return $query->result_array();
			}
		}
		return null;
	}
}