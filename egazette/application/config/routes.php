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
$route['default_controller'] = 'website/index';
$route['contact_us'] = 'website/contact';
$route['contact_email'] = 'website/contact_mail';
$route['about_us'] = 'website/about_us';
$route['about_gazette'] = 'website/about_gazette';
$route['copyright_policy'] = 'website/copyright_policy';
$route['terms_conditions'] = 'website/terms_conditions';
$route['privacy_policy'] = 'website/privacy_policy';
$route['feedback_us'] = 'website/feedback';
$route['name_surname'] = 'website/name_surname';
$route['partnership'] = 'website/partnership';
$route['grievance'] = 'website/grievance'; // this work is pending
$route['payment'] = 'payment/index';
$route['payment/gateway'] = 'payment/gateway';
$route['archive'] = 'website/archive';
$route['search_gazette'] = 'website/search_gazette';
$route['screen_reader'] = 'website/screen_reader';
$route['weekly'] = 'website/view_weekly';
$route['weekly/(:any)'] = 'website/view_weekly/$1';
$route['extraordinary'] = 'website/view_extraordinary';
$route['extraordinary/(:any)'] = 'website/view_extraordinary/$1';

$route['bills_acts'] = 'website/bills_acts';
$route['bills_acts/(:any)'] = 'website/bills_acts/$1';
$route['land_acquisition'] = 'website/land_acquisition';
$route['land_acquisition/(:any)'] = 'website/land_acquisition/$1';
$route['surname_change'] = 'website/surname_partner_change';
$route['surname_change/(:any)'] = 'website/surname_partner_change/$1';
$route['other_gazette'] = 'website/other_gazette';
$route['other_gazette/(:any)'] = 'website/other_gazette/$1';
$route['change_of_partnership_details'] = 'website/change_of_partnership_details';
$route['change_of_partnership_details/(:any)'] = 'website/change_of_partnership_details/$1';
$route['change_name_surname'] = 'website/change_name_surname';
$route['change_name_surname/(:any)'] = 'website/change_name_surname/$1';

$route['disclaimer'] = 'website/disclaimer';
$route['search_gazette_result'] = 'website/search_gazette_result';
$route['search_gazette_result/(:any)'] = 'website/search_gazette_result/$1';
// To be removed once the e-Sign implemented
//$route['esign'] = 'website/esign_sample';

//Archival ROUTES
$route['extraordinary_archival'] = 'website/extraordinary_archival';
$route['extraordinary_archival/(:any)'] = 'website/extraordinary_archival/$1';
$route['weekly_archival'] = 'website/weekly_archival';
$route['weekly_archival/(:any)'] = 'website/weekly_archival/$1';
$route['extraordinary_archival_search'] = 'website/archival_filter_ext';
$route['extraordinary_archival_search/(:any)'] = 'website/archival_filter_ext/$1';
$route['weekly_archival_search'] = 'website/archival_filter_week';
$route['weekly_archival_search/(:any)'] = 'website/archival_filter_week/$1';


$route['extraordinary_archival_dept/(:num)/(:num)'] = 'website/extraordinary_archival_dept/$1/$2';
$route['extraordinary_archival_dept_search/(:any)'] = 'website/extraordinary_archival_dept_search/$1';



$route['users'] = 'user/index';
$route['dashboard'] = 'user/dashboard';

$route['404_override'] = 'Error404/error_404';
$route['translate_uri_dashes'] = FALSE;

/*
 * BOTDETECT CAPTCHA ROUTES
 */
$route['botdetect/captcha-handler'] = 'botdetect/captcha_handler/index';
?>