<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

//// member joining and creating account.
$routes->get('join-now', 'CreateMemberController::index');
$routes->post('join-now/store', 'CreateMemberController::storeClient');
$routes->get('verify-email/(:any)', 'CreateMemberController::verifyEmail/$1');
$routes->get('verify-email/(:any)', 'CustomerController::verifyEmail/$1');
$routes->get('redirect', 'CreateMemberController::redirect');
$routes->post('/resend-verification', 'CreateMemberController::resendVerification');
$routes->get('/resendtoken', 'CreateMemberController::resendToken');
$routes->get('/verify/(:any)', 'CreateMemberController::verify/$1');
$routes->get('/verify/(:any)', 'CustomerController::verify/$1');

/// user forget password
$routes->get('forgot-password', 'CreateMemberController::forgotPassword');
$routes->post('forgot-password', 'CreateMemberController::sendResetLink');
$routes->get('reset-password', 'CreateMemberController::showResetForm/$1');
$routes->post('reset-password', 'CreateMemberController::resetPassword');
$routes->get('reset-password/(:any)', 'CreateMemberController::resetPasswords/$1');
//update pass naman

$routes->post('update-password', 'CreateMemberController::updatePassword');



//user-login clients logn
$routes->get('/member-login', 'LoginClientController::LoginClient');
$routes->post('/login/authenticate', 'LoginClientController::authenticate');
$routes->post('/logout', 'LoginClientController::logout');
///// client dashboard/




          ////LANDINGPAGE\\\\\
 $routes->get('/', 'Home::index');


                ////////////////////ADMIN DASHBOARD/////////////////////
 // admin login/ Dashboard, Scan your ID, Participant Log, Manage Client, Manage Coach, Manage Equipment,Plans, Logout///
 
 $routes->get('/joinus', 'Home::joinus');
 ///admin dashboard
 $routes->get('/admin', 'Admin::index' );
                    
 ///Scan ur ID...
$routes->post('scan-qr/save/(:num)', 'QrAttendanceController::save/$1');
$routes->get('scan-qr/save/(:num)', 'QrAttendanceController::save/$1');
$routes->get('scan-qr', 'QrAttendanceController::viewqrcode');
                
////participant log...
$routes->get('/attendance', 'AttendanceLogController::checkin');
$routes->get('/checkout/(:any)', 'AttendanceLogController::checkout/$1');
                
////Manage Client.... (clients1,(edit,update,delete,store)) ////
$routes->get('/clients1', 'CustomerController::index');
$routes->get('/clients1/create', 'CustomerController::createClients1');
$routes->post('/clients1/store', 'CustomerController::storeClients1');
$routes->get('/clients1/edit/(:num)', 'CustomerController::editClients1/$1');
$routes->post('/clients1/update/(:num)', 'CustomerController::updateClients1/$1');
$routes->delete('/clients1/delete/(:num)', 'CustomerController::deleteClients1/$1');
$routes->get('clients1/renew', 'CustomerController::renew');

///BUTTONS/// Freeze | View \\\\\\
$routes->post('/customer/toggleFreeze/(:num)', 'CustomerController::toggleFreeze/$1');
$routes->get('/clients1/view/(:num)', 'CustomerController::viewClient/$1');

///Manage Coach... (coach,(edit,update,delete,store)) ////
$routes->get('/coach', 'CoachController::index' );
//$routes->get('/client/create', 'CoachController::createClient');
$routes->post('/coach/store', 'CoachController::storeClient');
$routes->get('/coach/edit/(:num)', 'CoachController::edit/$1');
$routes->post('/coach/update/(:num)', 'CoachController::update/$1');
$routes->delete('/coach/delete/(:num)', 'CoachController::deleteCoach/$1');
$routes->get('/coach/(:num)', 'CoachController::deleteClient/$1');

///Manage Equipment... (gymequipment,(edit,update,delete,store)) ////
$routes->get('/gymequipment', 'EquipmentController::index' );
///$routes->get('/gymequipment/create', 'CoachController::create');
$routes->post('/gymequipment/store', 'EquipmentController::storeEquipment');
$routes->get('/gymequipment/(:num)', 'EquipmentController::deleteEquipment/$1');
$routes->post('/gymequipment/(:num)', 'EquipmentController::updateEquipment/$1');
$routes->get('/gymequipment/edit/(:num)', 'EquipmentController::edit/$1');
$routes->post('/gymequipment/update/(:num)', 'EquipmentController::update/$1');
$routes->delete('/gymequipment/delete/(:num)', 'EquipmentController::deleteEquipment/$1');

///Manage Plans... (gymplans,(edit,update,delete,store)) ////
$routes->get('/gymplans', 'PlanController::indexgymplan');
$routes->post('/gymplans/store', 'PlanController::storegymplan' );
$routes->get('/gymplans/store', 'PlanController::storegymplan');
$routes->get('/gymplans/edit/(:num)', 'PlanController::edit/$1');
$routes->post('/gymplans/update/(:num)', 'PlanController::update/$1');
$routes->delete('/gymplans/delete/(:num)', 'PlanController::delete/$1');
/// adding fetching  plans and coaches
$routes->get('/fetchPlans', 'CustomerController::getPlans');
$routes->get('/fetchCoachPlan', 'CustomerController::getCoaches');

///Logout...
$routes->get('/logout', 'Admin::logout');


///////////////////////CLIENT DASHBOARD/////////////////////    
 /// Dashboard, My QR Code, Todo List, View Gym Equipment, Body Information, Logout///
 $routes->get('/clientdashboard', 'ClientsDashboardController::index');

///My QR Code...
$routes->get('/myqrcode', 'ClientsDashboardController::myqrcode');

///Todo List...

///View Gym Equipment...
$routes->get('/viewequipment', 'ViewEquipmentController::indexviewequipment');

///Body Information...

///Logout...


///////////////////////COACH DASHBOARD/////////////////////   
 //// Coach Login/ Coach Dashboard, Manage my Schedule, View my CLients, View Gym Equipment, Logout/// 
 $routes->get('/coach-login', 'LoginCoachController::LoginCoach');
$routes->post('/coach/authenticate', 'LoginCoachController::authenticate1');
// Coach Dashboard
$routes->get('/coachdashboard', 'CoachDashboardController::index');

/// Manage my Schedule
///Manage Coach... (coach,(edit,update,delete,store)) ////
$routes->get('/coach-manage', 'CoachDashboardController::coachManage' );
$routes->post('/coach-manage/store', 'CoachDashboardController::storemanage');
$routes->get('/coach-manage/edit/(:num)', 'CoachDashboardController::edit/$1');
$routes->post('/coach-manage/update/(:num)', 'CoachDashboardController::update/$1');
$routes->delete('/coach-manage/delete/(:num)', 'CoachDashboardController::delete/$1');

///View my Clients... and time manage
$routes->get('/coach-timemanage', 'CoachDashboardController::coachtimeManage' );
$routes->post('/coach-timemanage/store', 'CoachDashboardController::timestore');
$routes->get('/coach-timemanage/edit/(:num)', 'CoachDashboardController::editTime/$1');
$routes->post('/coach-timemanage/update/(:num)', 'CoachDashboardController::updateTime/$1');
$routes->delete('/coach-timemanage/delete/(:num)', 'CoachDashboardController::deleteTime/$1');
///$routes->get('/coach-clientlist', 'CoachDashboardController::coachclientlist');

///View Gym Equipment...

///Logout...