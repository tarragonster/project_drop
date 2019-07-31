<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$route['login'] = 'index/login';
$route['logout'] = 'index/logout';
$route['lockscreen'] = 'index/lockscreen';
$route['product/(:num)'] = 'product/index/$1';
$route['user/(:num)'] = 'user/index/$1';
$route['dashboard'] = 'index/index';
$route['forgotPassword'] = 'index/forgotPassword';
$route['reset-password/(:any)'] = 'index/resetPassword/$1';