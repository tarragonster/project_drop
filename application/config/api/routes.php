<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//product
$route['product/(:num)'] = 'product/get/$1';
$route['product/(:num)/share'] = 'product/share/$1';
$route['product/(:num)/reviews'] = 'product/reviews/$1';
$route['product/(:num)/captions'] = 'product/captions/$1';
$route['product/(:num)/numWatching'] = 'product/numWatching/$1';
$route['recentlyWatched'] = 'product/recentlyWatched';

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
$route['user/following/(:num)'] = 'user/following/$1';
$route['user/followers/(:num)'] = 'user/followers/$1';
