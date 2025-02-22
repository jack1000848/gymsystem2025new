<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/admin', 'Admin::index');
$routes->get('/joinus', 'Home::joinus');

//// member joining and creating account.
$routes->get('join-now', 'CreateMemberController::index');
$routes->post('join-now/store', 'CreateMemberController::storeClient');


//user-login clients logn
$routes->get('/member-login', 'LoginClientController::LoginClient');
$routes->post('/login/authenticate', 'LoginClientController::authenticate');
$routes->post('/logout', 'LoginClientController::logout');
///// client dashboard/
$routes->get('/clientdashboard', 'ClientsDashboardController::index');
///coach dashboard/login 
$routes->get('/coachdashboard', 'CoachDashboardController::index');
$routes->get('/coach-login', 'LoginCoachController::LoginCoach');
$routes->post('/coach/authenticate', 'LoginCoachController::authenticate1');

//clientx coach na to! routes  admin dashboard
$routes->get('/coach', 'CoachController::index');
//$routes->get('/client/create', 'CoachController::createClient');
$routes->post('/client/store', 'CoachController::storeClient');
$routes->get('/client/edit/(:num)', 'CoachController::editClient/$1');
$routes->post('/client/update/(:num)', 'CoachController::updateClient/$1');
$routes->get('/client/(:num)', 'CoachController::deleteClient/$1');

//QR ATTENDANCE
$routes->get('/qr-attendance', 'QrAttendanceController::scanQrCode');

///gym equipment!  admin dashboard
$routes->get('/gymequipment', 'EquipmentController::index');
///$routes->get('/gymequipment/create', 'CoachController::create');
$routes->post('/gymequipment/store', 'EquipmentController::storeEquipment');
$routes->get('/gymequipment/(:num)', 'EquipmentController::deleteEquipment/$1');

$routes->get('/fetchPlans', 'CustomerController::getPlans');
$routes->get('/fetchCoachPlan', 'CustomerController::getCoaches');

///admin dashboard gym plans kunno
$routes->get('/gymplans', 'PlanController::indexgymplan');
$routes->post('/gymplans/store', 'PlanController::storegymplan');





/// clients1 routes   client dashboard
$routes->get('/clients1', 'CustomerController::index');
$routes->get('/clients1/create', 'CustomerController::createClients1');
$routes->post('/clients1/store', 'CustomerController::storeClients1');
$routes->get('/clients1/edit/(:num)', 'CustomerController::editClients1/$1');
$routes->get('/clients1/update/(:num)', 'CustomerController::updateClients1/$1');
$routes->get('/clients1/delete/(:num)', 'CustomerController::deleteClients1/$1');
$routes->get('clients1/renew', 'CustomerController::renew');
$routes->get('test-db', 'CustomerController::testDatabase');


///viewing gym equipments forclients dashboard
$routes->get('/viewequipment', 'ViewEquipmentController::indexviewequipment');


////qr code practice

$routes->get('/qrcode', 'QrCodeController::index');
$routes->post('/qrcode/generate', 'QrCodeController::generate');
$routes->get('/qrcode/list', 'QrCodeController::list');






