<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['gcm_api_key'] = 'AIzaSyCSj1mK1ZNHzJer35H8iDB8vN0ZSxhY-Og';
$config['push_table'] = 'notification_queue';

$config['apns_server'] = 'gateway.push.apple.com:2195';
$config['apns_cert'] = APPPATH . 'libraries/crts/push_pro.pem';
$config['apns_passphrase'] = '123';
//Live
// $config['apns_server'] = 'gateway.sandbox.push.apple.com:2195';
// $config['apns_cert'] = APPPATH . 'libraries/crts/push_ss.pem';
// $config['apns_passphrase'] = '123';
