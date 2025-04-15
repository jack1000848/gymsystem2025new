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
$routes->get('reset-password/(:any)', 'CreateMemberController::showResetForm/$1');
$routes->post('reset-password', 'CreateMemberController::resetPassword');
///$routes->post('reset-password/(:any)', 'CreateMemberController::resetPassword/$1');
//update pass naman

///coach forgot pass///
$routes->get('coach-forgot-password', 'LoginCoachController::forgotPassword');
$routes->post('send-reset-link', 'LoginCoachController::sendResetLink');
$routes->get('coach-reset-password/(:segment)', 'LoginCoachController::showResetForm/$1');
$routes->post('coach-reset-password', 'LoginCoachController::resetPassword');



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
 $routes->post('/admin-login', 'Admin::login');
 ///admin dashboard
 $routes->get('/admin', 'Admin::index' );
                    
 ///Scan ur ID...
$routes->post('scan-qr/save/(:num)', 'QrAttendanceController::save/$1');
$routes->get('scan-qr/save/(:num)', 'QrAttendanceController::save/$1');
$routes->get('scan-qr', 'QrAttendanceController::viewqrcode');
///coachqrattendance
$routes->get('coachattendanceqr', 'QrAttendanceController::viewqrcodecoach');
$routes->post('coachattendanceqr/save/(:num)', 'QrAttendanceController::save1/$1');
$routes->get('coachattendanceqr/save/(:num)', 'QrAttendanceController::save1/$1');
                
////participant log...
$routes->get('/attendance', 'AttendanceLogController::checkin');
$routes->get('/checkout/(:any)', 'AttendanceLogController::checkout/$1');
$routes->get('/coachattendance', 'AttendanceLogController::coachattendance');
$routes->get('/coachattendance/(:any)', 'AttendanceLogController::coachcheckout/$1');
                
////Manage Client.... (clients1,(edit,update,delete,store)) ////
$routes->get('/clients1', 'CustomerController::index');
$routes->get('/clients1/create', 'CustomerController::createClients1');
$routes->post('/clients1/store', 'CustomerController::storeClients1');
$routes->get('/clients1/edit/(:num)', 'CustomerController::editClients1/$1');
$routes->post('/clients1/update/(:num)', 'CustomerController::updateClients1/$1');
$routes->get('/clients1/update/(:num)', 'CustomerController::updateClients1/$1');
$routes->delete('/clients1/delete/(:num)', 'CustomerController::deleteClients1/$1');
//$routes->get('clients1/renew', 'CustomerController::renew');

///BUTTONS/// Freeze | View | renew \\\\\\
$routes->post('/customer/toggleFreeze/(:num)', 'CustomerController::toggleFreeze/$1');
$routes->get('/clients1/view/(:num)', 'CustomerController::viewClient/$1');
///$routes->get('/clients1/renew/(:num)', 'CustomerController::renew/$1');
///$routes->post('/clients1/renew/(:num)', 'CustomerController::renew/$1');
$routes->get('/clients1/renew/(:num)', 'CustomerController::try/$1');
$routes->post('/clients1/renewupdate/(:num)', 'CustomerController::updaterenew/$1');
$routes->get('/clients1/renewupdate/(:num)', 'CustomerController::updaterenew/$1');

///Manage Coach... (coach,(edit,update,delete,store)) ////
$routes->get('/coach', 'CoachController::index' );
//$routes->get('/client/create', 'CoachController::createClient');
$routes->post('/coach/store', 'CoachController::storeClient');
$routes->get('/coach/edit/(:num)', 'CoachController::edit/$1');
$routes->post('/coach/update/(:num)', 'CoachController::update/$1');
$routes->get('/coach/update/(:num)', 'CoachController::update/$1');
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

///View Schedule...
$routes->get('/view-schedule', 'ViewScheduleForAllUserController::adminview');

///Logout...
$routes->get('/logout', 'Admin::logout');


///////////////////////CLIENT DASHBOARD/////////////////////    
 /// Dashboard, My QR Code, Todo List, View Gym Equipment, Body Information, Logout///
 $routes->get('/clientdashboard', 'ClientsDashboardController::index');
 
 /// view my attendance
$routes->get('/view-attendance', 'ClientsDashboardController::viewAttendance');
///My QR Code...
$routes->get('/client-qr', 'ClientsDashboardController::myqrcode');

/// view client coach absent
$routes->get('client/coach-absence', 'ClientsDashboardController::coachAbsenceNotification');

///Todo List...

///View Gym Equipment...
$routes->get('/viewequipment', 'ViewEquipmentController::indexviewequipment');

///Body Information...
$routes->get('/bodyInfo', 'ClientsDashboardController::bodyInfo');
$routes->post('/customer/body/save', 'ClientsDashboardController::saveBodyInfo');
$routes->get('/customer/body/history', 'ClientsDashboardController::bodyHistory');
// view my schedule
$routes->get('/viewmyschedule', 'ViewScheduleForAllUserController::clientview');

/// account setting
$routes->get('/account-setting', 'ClientsDashboardController::accountSettings');
$routes->post('/update-account', 'ClientsDashboardController::updateAccount');
///Logout...
$routes->get('/logout', 'ClientsDashboardController::logout');


$routes->get("/getCoachSchedules/(:num)", "CustomerController::getSchedules/$1");
$routes->get("/getCoachSchedules1/(:num)", "CreateMemberController::getSchedules1/$1");

///////////////////////COACH DASHBOARD/////////////////////   
 //// Coach Login/ Coach Dashboard, Manage my Schedule, View my CLients, View Gym Equipment, Logout/// 
 $routes->get('/coach-login', 'LoginCoachController::LoginCoach');
$routes->post('/coach/authenticate', 'LoginCoachController::authenticate1');
// Coach Dashboard
$routes->get('/coachdashboard', 'CoachDashboardController::dashboardindex');
$routes->post('/coach/markAbsence', 'CoachDashboardController::markAbsence');
$routes->get('/coach-markAbsence', 'CoachDashboardController::markAbsenceForm');
///coach view my attendance\\\\
$routes->get('/mylogs', 'CoachDashboardController::mylogs');
/// Manage my Schedule
///Manage Coach... (coach,(edit,update,delete,store)) ////
$routes->get('/coach-manage', 'CoachDashboardController::coachManage');
$routes->post('coach-manage/store', 'CoachDashboardController::storemanage');
$routes->get('coach-manage/store', 'CoachDashboardController::storemanage');
$routes->get('/coach-manage/update', 'CoachDashboardController::update');
$routes->get('/coach-manage/edit/(:num)', 'CoachDashboardController::edit/$1');
$routes->get('coach-manage/delete/(:num)', 'CoachDashboardController::delete/$1');
$routes->post('coach-manage/delete/(:num)', 'CoachDashboardController::delete/$1');

$routes->delete('/coach-manage/delete/(:num)', 'CoachDashboardController::delete/$1');

///View my Clients... and time manage
$routes->get('/viewmyclients', 'ViewScheduleForAllUserController::coachview');
                                
///$routes->get('/coach-clientlist', 'CoachDashboardController::coachclientlist');


///coach-qr
$routes->get('/coach-qr', 'CoachDashboardController::coachqr' );

//// view equipments
$routes->get('/viewequipment1', 'ViewEquipmentController::indexviewequipment1');
/// account setting
$routes->get('/account-setting1', 'CoachDashboardController::accountSettings');
$routes->post('/update-account1', 'CoachDashboardController::updateAccount');
///Logout...
$routes->get('/coach-logout', 'CoachDashboardController::logout');




////// add task kunno
$routes->get('tasks/create', 'TaskController::create');
$routes->post('tasks/store', 'TaskController::store');
$routes->get('tasks/coach', 'TaskController::coachTasks');
$routes->get('tasks/client', 'TaskController::clientTasks');
$routes->get('tasks/complete/(:num)', 'TaskController::complete/$1');


$routes->post('tasks/updateProgress/(:num)', 'TaskController::updateProgress/$1');
$routes->post('tasks/updateStatus/(:num)', 'TaskController::updateStatus/$1');

//// new
$routes->post('tasks/updateSubtasks/(:num)', 'TaskController::updateSubtasks/$1');


    $routes->get('create', 'TaskController::create');
    $routes->post('store', 'TaskController::store');
    $routes->get('coach', 'TaskController::coachTasks');
    $routes->get('client', 'TaskController::clientTasks');
    $routes->get('complete/(:num)', 'TaskController::complete/$1');
    $routes->post('update-progress/(:num)', 'TaskController::updateProgress/$1');
    $routes->post('update-subtasks/(:num)', 'TaskController::updateSubtasks/$1');
    $routes->get('update-status/(:num)', 'TaskController::updateStatus/$1');
    $routes->post('save-task-status/(:num)', 'TaskController::saveTaskStatus/$1');
    $routes->get('download-pdf/(:num)', 'TaskController::downloadPdf/$1');
