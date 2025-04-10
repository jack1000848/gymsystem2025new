<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CreateMemberModel;
use App\Models\CoachModel;
use App\Models\ViewScheduleForAllUserModel;

// eto sa qr

use App\Models\QrCodeModel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Color\Color;


class CreateMemberController extends BaseController


{
  public function testDatabase()
  {
      $db = \Config\Database::connect();
      if ($db->connect()) {
          echo "Database connected successfully.";
      } else {
          echo "Database connection failed.";
      }
  }
    public function index()
    {
        $fetchClients1 =new CreateMemberModel();
        $data['join-now'] = $fetchClients1 ->findAll();

      
        return view('create_member/createmember', $data);
    }

    public function linkcoach()
    {
        $coachModel = new CoachModel();
        $coaches = $coachModel->getCoaches();
        
        $data['coaches'] = $coaches;

        // Render the view with the data
        return view('clients1crud/add', $data);
    }



    public function createClients1()
    {
        $data['clients1Password'] = '20_'. uniqid();
        $data['gymcode'] = '155_' . uniqid();
        
        //qr
    
        return view('create_member/createmember', $data);
    }
    public function storeClient()
     {
        $insertClients = new CreateMemberModel ();
        
         // Retrieve the email from the form input
    $email = $this->request->getPost('clients1Emailaddress');

    // Check if email is retrieved properly
    if (empty($email)) {
        return redirect()->back()->with('error', 'Email field is required.');
    }

    // Check if the email is a Gmail address
    if (!preg_match("/^[a-zA-Z0-9._%+-]+@gmail\.com$/", $email)) {
        return redirect()->back()->with('error', 'Only Gmail addresses are allowed.');
    }

       // Generate unique token
    $token = bin2hex(random_bytes(50));

        $data = [
            'CustomerID'       => $this->request->getPost('gymcode'),                 // Maps directly
            'Firstname'        => $this->request->getPost('clients1Fname'),           // Maps directly
            ///'Middlename'       => $this->request->getPost('clients1Mname') ?? null,   // Add if required
            'Lastname'         => $this->request->getPost('clients1Lname'),           // Adjusted field name
            'Address'          => $this->request->getPost('clients1Username'),     // Adjusted field name
            'Gender'           => $this->request->getPost('gender'),                  // Maps directly
          // 'PhoneNumber'      => $this->request->getPost('phone_number'),            // Add phone field
            'Email'            => $this->request->getPost('clients1Emailaddress'),    // Adjusted field name
            'password_hash'         =>  password_hash($this->request->getPost('password'), PASSWORD_BCRYPT), // Hash the password
            'RegisteredDate'   => $this->request->getPost('dateofregistration'), 
           /// 'GymTimeSlot'       => $this->request->getPost('timeslot'),
            'types_of_workout'   => $this->request->getPost('tworkout'),                   // Maps directly
            'Membesrship_plan'   => $this->request->getPost('plans'),      // Adjusted field name
            'WorkoutTypeID'    => null,                // Adjusted field name
            'CurrentPlanID'    => null,                   // Adjusted field name     
            'WorkoutPlanID'    =>  null, // Add if necessary
            'CoachID'    =>  $this->request->getPost('plans'), // Add if necessary
            'verification_token' => $token,
            'is_verified' => 0

        ];
        

        

        if ($insertClients->save($data)) {


            $customerId = $insertClients->getInsertID();

            // Retrieve schedule IDs and coach ID
            $scheduleIds = $this->request->getPost('coachsched'); // This should be an array
            $coachId = $this->request->getPost('coach');
        
            // Update the CoachSched table for each selected schedule
            if (!empty($scheduleIds) && !empty($coachId)) {
                $db = \Config\Database::connect();
                $builder = $db->table('CoachSched');
        
                foreach ($scheduleIds as $schedId) {
                    $builder->where('CoachID', $coachId)
                            ->where('ID', $schedId)
                            ->update(['CustomerID' => $customerId]);
                }
            }
                

            // Send verification email
            $this->sendVerificationEmail($data['Email'], $token);
    
            session()->setFlashdata('success', 'Account created successfully! Please verify your email.');
            return redirect()->to('/redirect');
        } else {
            session()->setFlashdata('error', 'Failed to register.');
            return redirect()->back();
        
        } }
    
        private function sendVerificationEmail($email, $token)
        {
            $emailService = service('email');
        
            $emailService->setTo($email);
            $emailService->setFrom('taysonmiguelito125@gmail.com', 'IshowFitnessGYM');
            $emailService->setSubject('Email Verification');
            $emailService->setMessage("Hello,

Thank you for signing up! To complete your registration and verify your email address, please click the link below: <a href='" . base_url("verify-email/$token") . "'>Verify Email</a>");
        
            if (!$emailService->send()) {
                log_message('error', $emailService->printDebugger(['headers']));
            }
        }

        public function verifyEmail($token)
        {
            $memberModel = new CreateMemberModel();
            $user = $memberModel->where('verification_token', $token)->first();
        
            if ($user) {
                $memberModel->update($user['CustomerID'], ['is_verified' => 1, 'verification_token' => null]);
                session()->setFlashdata('success', 'Email verified successfully! You can now log in.');
                return redirect()->to('/member-login');
            } else {
                session()->setFlashdata('error', 'Invalid verification link.');
                return redirect()->to('/member-login');
            }
        }

        
    public function resendToken()
        {
            return view('create_member/resendtoken'); // Correct path to the view
        }
    public function resendVerification()
    {
        $email = $this->request->getPost('clients1Emailaddress'); // Match with form input name
        $userModel = new CreateMemberModel(); // Ensure using correct model
        
        $user = $userModel->where('Email', $email)->first();

        if (!$user) {
            return redirect()->to()->with('error', 'Email not found.');
        }

        if ($user['is_verified'] == 1) {
            return redirect()->back()->with('error', 'Your account is already verified.');
        }

        // Generate a new token
        $newToken = bin2hex(random_bytes(50));

        // Update token in the database
        $userModel->update($user['CustomerID'], ['verification_token' => $newToken]);

        // Send the new verification email
       // $verificationLink = base_url("verify/$newToken");
       // $message = "Click the link to verify your account: <a href='$verificationLink'>$verificationLink</a>";

        // Send email (ensure email is configured in .env)
        $emailService = \Config\Services::email();
        $emailService->setTo($email);
        $emailService->setFrom('taysonmiguelito125@gmail.com', 'IshowFitnessGYM');
        $emailService->setSubject('Email Verification');
        $emailService->setMessage(" hi,
            Click the link to verify your account: <a href='" . base_url("verify-email/$newToken") . "'>Verify Email</a>");

        if ($emailService->send()) {
            return redirect()->back()->with('success', 'Verification email has been resent. Please check your inbox.');
        } else {
            return redirect()->back()->with('error', 'Failed to send verification email.');
        }
        
    }

    public function verify($token)
    {
        $userModel = new CreateMemberModel(); // Ensure using correct model
        $user = $userModel->where('verification_token', $token)->first();

        if (!$user) {
            return redirect()->to('/loginclient')->with('error', 'Invalid or expired verification token.');
        }

        // Mark user as verified
        $userModel->update($user['CustomerID'], ['is_verified' => 1, 'verification_token' => null]);

        return redirect()->to('/loginclient')->with('success', 'Your account has been verified. You can now log in.');
    }


        public function redirect(){
            return view('create_member/redirect');

            
        }


/// forget password users
public function forgotPassword()
{
    return view('member_resetpassword/forgotpassword');
}

public function sendResetLink()
{
    $email = $this->request->getPost('email');

    $userModel = new CreateMemberModel();
    $user = $userModel->where('Email', $email)->first();

    if (!$user) {
        return redirect()->to('/forgot-password')->with ('error', 'Email not found.');
    }

    // Generate reset token
    $token = bin2hex(random_bytes(50));
    $expiry = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token expires in 1 hour

    // Save token in the database
    $userModel->update($user['CustomerID'], [
        'reset_token' => $token,
        'reset_token_expires' => $expiry
    ]);

    // Send email
    //$resetLink = base_url("reset-password/$token");
  //  $message = "Click the link to reset your password: <a href='$resetLink'>$resetLink</a>";

    $emailService = service('email');
    $emailService->setTo($email);
    $emailService->setFrom('taysonmiguelito125@gmail.com', 'IshowFitnessGYM');
    $emailService->setSubject('Password Reset Request');
    $emailService->setMessage("Hi, Click the link to reset your password: <a href='" . base_url("reset-password/$token") . "'>Reset Password</a>");
   
    if ($emailService->send()) {
        return redirect()->back()->with('success', 'Reset link sent. Check your email.');

    } else {
        return redirect()->to()->with('error', 'Failed to send email.');
    }
}


   public function showResetForm($token)
{
    $userModel = new CreateMemberModel();
    $user = $userModel->where('reset_token', $token)->first();

    if (!$user || strtotime($user['reset_token_expires']) < time()) {
        return redirect()->to('/forgot-password')->with('error', 'Invalid or expired reset link.');
}

    return view('member_resetpassword/resetpassword', ['token' => $token]);
}
///public function showResetForm($token)
///{   
 ///   $userModel = new CreateMemberModel();
  //  $user = $userModel->where('reset_token', $token)->first();

  //  if (!$user || strtotime($user['reset_token_expires']) < time()) {
  //      return redirect()->to('/forgot-password')->with('error', 'Invalid or expired reset link1.');
//}
  //  return view('member_resetpassword/resetpassword', ['token' => $token]);
//}

public function resetPassword()
{
    $token = $this->request->getPost('token');
    $password = $this->request->getPost('password');

    // Debugging: Log received token and password
    log_message('debug', 'Token received: ' . $token);
    log_message('debug', 'Password received: ' . $password);

    $userModel = new CreateMemberModel();
    $user = $userModel->where('reset_token', $token)->first();

    if (!$user) {
        log_message('error', 'User not found for token: ' . $token);
        return redirect()->to('/forgot-password')->with('error', 'Invalid or expired reset link.');
    }

    if (strtotime($user['reset_token_expires']) < time()) {
        log_message('error', 'Token expired for user ID: ' . $user['CustomerID']);
        return redirect()->to('/forgot-password')->with('error', 'Expired reset link.');
    }

    // Hash the new password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    log_message('debug', 'Hashed password: ' . $hashedPassword);

    // Try updating the password
    $updateData = [
        'password_hash' => $hashedPassword,  // Ensure this field matches your DB column
        'reset_token' => null,
        'reset_token_expires' => null
    ];

    if ($userModel->update($user['CustomerID'], $updateData)) {
        log_message('debug', 'Password updated successfully for user ID: ' . $user['CustomerID']);
        return redirect()->to('/member-login')->with('success', 'Password reset successfully.');
    } else {
        log_message('error', 'Password update failed for user ID: ' . $user['CustomerID']);
        return redirect()->to('/forgot-password')->with('error', 'Something went wrong. Try again.');
    }
}


public function getSchedules1($id){

    $scheduleModel = new \App\Models\ViewScheduleForAllUserModel();

    //find schedule where id is eq to CoachID and CustomerID is null
    $schedules = $scheduleModel->where('CoachID', $id)->where('CustomerID', null)->findAll();
    // Return the schedules data as JSON
    return $this->response->setJSON($schedules);


}

/// update the users pass;
///public function resetPasswords($token)
///{
 ///   $data['token'] = $token;
 ///   return view('member_resetpassword/resetpassword', $data);
///}


}