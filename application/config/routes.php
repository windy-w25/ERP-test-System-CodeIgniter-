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
|	https://codeigniter.com/userguide3/general/routing.html
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



// $route['default_controller'] = 'welcome';
// $route['404_override'] = '';
// $route['translate_uri_dashes'] = FALSE;

$route['dashboard'] = 'auth/dashboard';
$route['register'] = 'auth/register';

$route['item'] = 'item/index';
$route['item/create'] = 'item/create';
$route['item/edit/(:num)'] = 'item/edit/$1';
$route['item/delete/(:num)'] = 'item/delete/$1';

//old invoice
$route['create_invoice'] = 'invoice/create';
$route['get_item_details'] = 'invoice/get_item_details';

$route['customer'] = 'customer/index';
$route['unit'] = 'unit/index';

// new invoice form
$route['invoice'] = 'Invoice_controller/index';
$route['invoice/get_customer_address'] = 'Invoice_controller/get_customer_address';
$route['invoice/get_item_details'] = 'Invoice_controller/get_item_details';
$route['invoice/save_invoice'] = 'Invoice_controller/save_invoice';
$route['invoice/delete_invoice'] = 'Invoice_controller/delete_invoice';
$route['invoice/edit_invoice/(:num)'] = 'Invoice_controller/edit_invoice/$1';
$route['invoice/update_invoice/(:num)'] = 'Invoice_controller/update_invoice/$1';
$route['invoice/delete_invoice/(:num)'] = 'Invoice_controller/delete_invoice/$1';

//return invoice
$route['return_invoice'] = 'Return_invoice_controller/index';
$route['return_invoice/save'] = 'Return_invoice_controller/save_invoice';
$route['return_invoice/edit/(:num)'] = 'Return_invoice_controller/edit_invoice/$1';
$route['return_invoice/update/(:num)'] = 'Return_invoice_controller/update_invoice/$1';
$route['return_invoice/delete/(:num)'] = 'Return_invoice_controller/delete_invoice/$1';

$route['return_invoice/get_customer_invoices'] = 'Return_invoice_controller/get_customer_invoices';
$route['return_invoice/get_invoice_items_by_invoice_id'] = 'Return_invoice_controller/get_invoice_items_by_invoice_id';


