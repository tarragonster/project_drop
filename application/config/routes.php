<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['reset-password'] = 'home/resetPassword';
//admin
$route['admin'] = 'admin/index/index';
$route['admin/login'] = 'admin/index/login';
$route['admin/logout'] = 'admin/index/logout';
$route['admin/lockscreen'] = 'admin/index/lockscreen';

$route['admin/product/(:num)'] = 'admin/product/index/$1';
$route['admin/user/(:num)'] = 'admin/user/index/$1';