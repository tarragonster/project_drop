<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
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

//collectionn
$route['collection/list'] = 'collection/list';

//actor
$route['actor/(:num)'] = 'actor/get/$1';

//comment
$route['comments/(:num)/(:num)/(:num)'] = 'comment/get/$1/$2/$3';
$route['user/addComment'] = 'comment/add';

//search
$route['search/(:num)/(:any)'] = 'search/get/$1/$2';
$route['search/(:num)'] = 'search/get/$1';

//news
$route['news/(:num)'] = 'news/get/$1';

$route['user/(:num)'] = 'user/user/$1';
$route['admin/user/(:num)'] = 'admin/user/index/$1';

$route['user/following/(:num)'] = 'user/following/$1';

$route['user/followers/(:num)'] = 'user/followers/$1';