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
$route['admin'] = "admin/home";
$route['delivery_boy'] = "delivery_boy/home";
$route['delivery-boy'] = "delivery_boy/home";
$route['delivery-boy/(:any)'] = "delivery_boy/$1";
$route['delivery-boy/(:any)/(:any)'] = "delivery_boy/$1/$2";
$route['delivery-boy/(:any)/(:any)/(:any)'] = "delivery_boy/$1/$2/$3";
$route['delivery-boy/(:any)/(:any)/(:any)/(:any)'] = "delivery_boy/$1/$2/$3/$4";
$route['delivery-boy/(:any)/(:any)/(:any)/(:any)/(:any)'] = "delivery_boy/$1/$2/$3/$4/$5";
$route['products/(:num)'] = "products/index/$1";
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;

$route['about-us'] = "home/about-us";
$route['contact-us'] = "home/contact-us";
$route['faq'] = "home/faq";
$route['help'] = "home/help";
$route['getting-started-as-a-manufacturer'] = "home/get_started_mfg";
$route['getting-started-as-a-retailer'] = "home/get_started_retailer";
$route['navigating-your-dashboard'] = "home/navigating_your_dashboard";
$route['careers'] = "home/careers";
$route['privacy-policy'] = "home/privacy_policy";
$route['shipping-policy'] = "home/shipping_policy";
$route['return-policy'] = "home/return_policy";
$route['terms-of-use'] = "home/terms_and_conditions";
$route['quality-policy'] = "home/quality_policy";
$route['pricing-policy'] = "home/pricing_policy";
$route['delivery-policy'] = "home/delivery_policy";
$route['payment-policy'] = "home/payment_policy";
$route['security-policy'] = "home/security_policy";
$route['partnering-policy'] = "home/partnering_policy";
$route['licensing-policy'] = "home/licensing_policy";