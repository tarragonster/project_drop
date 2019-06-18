<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['gcm_api_key'] = 'AAAAGkvpT8c:APA91bEAaiPa7hoTyVyioHLEfdJRW1Ac13z6CF9vFz6C1HNo7eGBFtprxuujMimgLlCzHYz52-E1dC2w1VcD6umCMEGqYtkOBJHSiYB-llH5i8aNy0-EiMplvn09AOagvLJpjNoiYGyM'; // 112942731207
$config['push_table'] = 'notification_queue';

$config['apns_server'] = 'gateway.push.apple.com:2195';
$config['apns_cert'] = APPPATH . 'libraries/crts/10block.pem';
$config['apns_passphrase'] = '123';
//Live
// $config['apns_server'] = 'gateway.sandbox.push.apple.com:2195';
// $config['apns_cert'] = APPPATH . 'libraries/crts/push_ss.pem';
// $config['apns_passphrase'] = '123';
