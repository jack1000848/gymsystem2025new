<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/admin', 'Admin::index');
$routes->get('/logout', 'Admin::logout');

$routes->get('/joinus', 'Home::joinus');

//// member joining and creating account.
$routes->get('join-now', 'CreateMemberController::index');
$routes->post('join-now/store', 'CreateMemberController::storeClient');
$routes->get('verify-email/(:any)', 'CreateMemberController::verifyEmail/$1');
$routes->get('redirect', 'CreateMemberController::redirect');
$routes->post('/resend-verification', 'CreateMemberController::resendVerification');
$routes->get('/resendtoken', 'CreateMemberController::resendToken');
$routes->get('/verify/(:any)', 'CreateMemberController::verify/$1');

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
$routes->get('/clientdashboard', 'ClientsDashboardController::index');
///client dashboard viewqrcode
$routes->get('/myqrcode', 'ClientsDashboardController::myqrcode');
$routes->get('/clients1/view/(:num)', 'CustomerController::viewClient/$1');



///coach dashboard/login 
$routes->get('/coachdashboard', 'CoachDashboardController::index');
$routes->get('/coach-login', 'LoginCoachController::LoginCoach');
$routes->post('/coach/authenticate', 'LoginCoachController::authenticate1');

///coach dashboard/manage-sched
$routes->get('/coach-manage', 'CoachDashboardController::coachManage');
$routes->post('/coach-manage/store', 'CoachDashboardController::storemanage');
$routes->get('/coach-manage/edit/(:num)', 'CoachDashboardController::edit/$1');
$routes->post('/coach-manage/update/(:num)', 'CoachDashboardController::update/$1');
$routes->delete('/coach-manage/delete/(:num)', 'CoachDashboardController::delete/$1');
/////coach dashboard/time-sched
$routes->get('/coach-timemanage', 'CoachDashboardController::coachtimeManage');
$routes->post('/coach-timemanage/store', 'CoachDashboardController::timestore');
$routes->get('/coach-timemanage/edit/(:num)', 'CoachDashboardController::editTime/$1');
$routes->post('/coach-timemanage/update/(:num)', 'CoachDashboardController::updateTime/$1');
$routes->delete('/coach-timemanage/delete/(:num)', 'CoachDashboardController::deleteTime/$1');



////coach view all clients
$routes->get('/coach-clientlist', 'CoachDashboardController::coachclientlist');





//clientx coach na to! routes  admin dashboard
$routes->get('/coach', 'CoachController::index');
//$routes->get('/client/create', 'CoachController::createClient');
$routes->post('/coach/store', 'CoachController::storeClient');
$routes->get('/coach/edit/(:num)', 'CoachController::edit/$1');
$routes->post('/coach/update/(:num)', 'CoachController::update/$1');
$routes->delete('/coach/delete/(:num)', 'CoachController::deleteCoach/$1');
$routes->get('/coach/(:num)', 'CoachController::deleteClient/$1');

//QR ATTENDANCE
//$routes->get('/qr-attendance', 'QrAttendanceController::scanQrCode');

///gym equipment!  admin dashboard
$routes->get('/gymequipment', 'EquipmentController::index');
///$routes->get('/gymequipment/create', 'CoachController::create');
$routes->post('/gymequipment/store', 'EquipmentController::storeEquipment');
$routes->get('/gymequipment/(:num)', 'EquipmentController::deleteEquipment/$1');
$routes->post('/gymequipment/(:num)', 'EquipmentController::updateEquipment/$1');
$routes->get('/gymequipment/edit/(:num)', 'EquipmentController::edit/$1');
$routes->post('/gymequipment/update/(:num)', 'EquipmentController::update/$1');
$routes->delete('/gymequipment/delete/(:num)', 'EquipmentController::deleteEquipment/$1');

$routes->get('/fetchPlans', 'CustomerController::getPlans');
$routes->get('/fetchCoachPlan', 'CustomerController::getCoaches');

///admin dashboard gym plans kunno
$routes->get('/gymplans', 'PlanController::indexgymplan');
$routes->post('/gymplans/store', 'PlanController::storegymplan');
$routes->get('/gymplans/edit/(:num)', 'PlanController::edit/$1');
$routes->post('/gymplans/update/(:num)', 'PlanController::update/$1');
$routes->delete('/gymplans/delete/(:num)', 'PlanController::delete/$1');





/// clients1 routes   client dashboard
$routes->get('/clients1', 'CustomerController::index');
$routes->get('/clients1/create', 'CustomerController::createClients1');
$routes->post('/clients1/store', 'CustomerController::storeClients1');
$routes->get('/clients1/edit/(:num)', 'CustomerController::editClients1/$1');
$routes->post('/clients1/update/(:num)', 'CustomerController::updateClients1/$1');
$routes->delete('/clients1/delete/(:num)', 'CustomerController::deleteClients1/$1');
$routes->get('clients1/renew', 'CustomerController::renew');
$routes->get('test-db', 'CustomerController::testDatabase');
///freeze
$routes->post('/customer/toggleFreeze/(:num)', 'CustomerController::toggleFreeze/$1');



///viewing gym equipments forclients dashboard
$routes->get('/viewequipment', 'ViewEquipmentController::indexviewequipment');


////qr code practice

//routes->get('/qrcode', 'QrCodeController::index');
///$routes->post('/qrcode/generate', 'QrCodeController::generate');
///$routes->get('/qrcode/list', 'QrCodeController::list');


////qr code new att
$routes->get('scan-qr/save/(:any)', 'QrAttendanceController::save/$1');
$routes->post('/scan-qr/save/(:any)', 'QrAttendanceController::save/$1');
$routes->post('/scan-qr/delete/(:num)', 'QrAttendanceController::delete/$1');


///attendance for tapping qr
$routes->get('/attendance', 'AttendanceLogController::checkin');
$routes->get('/checkout/(:num)', 'AttendanceLogController::checkout/$1');



