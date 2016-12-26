<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['reset-password'] = 'home/resetPassword';
//admin
$route['admin'] = 'admin/index/index';
$route['admin/login'] = 'admin/index/login';
$route['admin/logout'] = 'admin/index/logout';
$route['admin/lockscreen'] = 'admin/index/lockscreen';

$route['admin/product/(:num)'] = 'admin/product/index/$1';

// api
//product
$route['product/(:num)'] = 'product/get/$1';

//collection
$route['collection/list'] = 'collection/list';
$route['feeds'] = 'collection/feeds';

//actor
$route['actor/(:num)'] = 'actor/get/$1';

//comment
$route['comments/(:num)/(:num)/(:num)'] = 'comment/get/$1/$2/$3';
$route['user/addComment'] = 'comment/add';

//search
$route['search/(:num)/(:any)'] = 'search/get/$1/$2';

//news
$route['news/(:num)'] = 'news/get/$1';

$route['user/(:num)'] = 'user/user/$1';

$route['user/following/(:num)'] = 'user/following/$1';

$route['user/followers/(:num)'] = 'user/followers/$1';