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

///coach login 
$routes->get('/coach-login', 'LoginCoachController::logincoach');
$routes->post('/coachlogin/authenticate', 'LoginCoachController::authenticate');

//user-login clients logn
$routes->get('/member-login', 'LoginClientController::LoginClient');
$routes->post('/login/authenticate', 'LoginClientController::authenticate');
$routes->post('/logout', 'LoginClientController::logout');
///// client dashboard/
$routes->get('/clientdashboard', 'ClientsDashboardController::index');
///coach dashboard
$routes->get('/coachdashboard', 'CoachDashboardController::index');
$routes->get('coach-login', 'LoginCoachController::LoginCoach');
$routes->post('/login/authenticate', 'LoginCoachController::authenticate1/');

//clientx coach na to! routes  admin dashboard
$routes->get('/coach', 'ClientsController::index');
//$routes->get('/client/create', 'ClientsController::createClient');
$routes->post('/client/store', 'ClientsController::storeClient');
$routes->get('/client/edit/(:num)', 'ClientsController::editClient/$1');
$routes->post('/client/update/(:num)', 'ClientsController::updateClient/$1');
$routes->get('/client/(:num)', 'ClientsController::deleteClient/$1');

//QR ATTENDANCE
$routes->get('/qr-attendance', 'QrAttendanceController::scanQrCode');

///gym equipment!  admin dashboard
$routes->get('/gymequipment', 'EquipmentController::index');
///$routes->get('/gymequipment/create', 'ClientsController::create');
$routes->post('/gymequipment/store', 'EquipmentController::storeEquipment');
$routes->get('/gymequipment/(:num)', 'EquipmentController::deleteEquipment/$1');


///admin dashboard gym plans kunno
$routes->get('/gymplans', 'PlanController::indexgymplan');
$routes->post('/gymplans/store', 'PlanController::storegymplan');





/// clients1 routes   client dashboard
$routes->get('/clients1', 'Clients1Controller::index');
$routes->get('/clients1/create', 'Clients1Controller::createClients1');
$routes->post('/clients1/store', 'Clients1Controller::storeClients1');
$routes->get('/clients1/edit/(:num)', 'Clients1Controller::editClients1/$1');
$routes->get('/clients1/update/(:num)', 'Clients1Controller::updateClients1/$1');
$routes->get('/clients1/delete/(:num)', 'Clients1Controller::deleteClients1/$1');
$routes->get('clients1/renew', 'Clients1Controller::renew');
$routes->get('test-db', 'Clients1Controller::testDatabase');


///viewing gym equipments forclients dashboard
$routes->get('viewequipment', 'ViewEquipmentController::indexviewequipment');


////qr code practice

$routes->get('/qrcode', 'QrCodeController::index');
$routes->post('/qrcode/generate', 'QrCodeController::generate');
$routes->get('/qrcode/list', 'QrCodeController::list');






