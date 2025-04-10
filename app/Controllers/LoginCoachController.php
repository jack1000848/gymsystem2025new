<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\LoginCoachModel;
use App\Models\CreateMemberModel;
    class LoginCoachController extends BaseController
{

    public function LoginCoach()
    {
        return view('coachdashboard/logincoach');     ///loginview 
    }
 

    public function authenticate1()
    {
       
        $session = session();
        $model = new LoginCoachModel();

        // Use email instead of username
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        // Use the updated method to get user by email
        $user = $model->getUserByEmail1($email);

        if ($user) {
            // Compare the password (Note: replace with hashed password checking later)
            if (password_verify($password, $user['password_hash'])) {  // Change to password_verify() if needed
                // Set session data
                $session->set([
                    'CoachID' => $user['CoachID'],
                    'Email' => $user['Email'],
                    'logged_in' => true,
                ]);
                session()->set('Role', "Coach"); // Set the role to "Coach"

                return redirect()->to('/coachdashboard'); // Redirect to the client dashboard
            } else {
                // Password mismatch
                return redirect()->back()->with('error', 'Invalid password.');
            }
        } else {
            // User not found
            return redirect()->back()->with('error', 'Email not found.');
        }
    }


/// forget password users
public function forgotPassword()
{
    return view('member_resetpassword/coachforgot');
}

public function sendResetLink()
{dd('Controller reached!');
    $email = $this->request->getPost('email');

    $userModel = new CreateMemberModel();
    $user = $userModel->where('Email', $email)->first();

    if (!$user) {
        return redirect()->to('/coach-forgot-password')->with ('error', 'Email not found.');
    }

    // Generate reset token
    $token = bin2hex(random_bytes(50));
    $expiry = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token expires in 1 hour

    // Save token in the database
    $userModel->update($user['CoachID'], [
        'reset_token' => $token,
        'reset_token_expires' => $expiry
    ]);

    

    $emailService = service('email');
    $emailService->setTo($email);
    $emailService->setFrom('taysonmiguelito125@gmail.com', 'IshowFitnessGYM');
    $emailService->setSubject('Password Reset Request');
    $emailService->setMessage("Hi, Click the link to reset your Coach Password: <a href='" . base_url("coach-reset-password/$token") . "'>Reset Password</a>");
   
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
        return redirect()->to('/coach-forgot-password')->with('error', 'Invalid or expired reset link.');
}

    return view('member_resetpassword/coach-reset-password', ['token' => $token]);
}


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
        return redirect()->to('/coach-forgot-password')->with('error', 'Invalid or expired reset link.');
    }

    if (strtotime($user['reset_token_expires']) < time()) {
        log_message('error', 'Token expired for user ID: ' . $user['CoachID']);
        return redirect()->to('/coach-forgot-password')->with('error', 'Expired reset link.');
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

    if ($userModel->update($user['CoachID'], $updateData)) {
        log_message('debug', 'Password updated successfully for user ID: ' . $user['CoachID']);
        return redirect()->to('/coach-login')->with('success', 'Password reset successfully.');
    } else {
        log_message('error', 'Password update failed for user ID: ' . $user['CoachID']);
        return redirect()->to('/coach-forgot-password')->with('error', 'Something went wrong. Try again.');
    }
}
    


}



?>