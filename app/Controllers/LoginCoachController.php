<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\LoginCoachModel;
use App\Models\CreateMemberModel;
use App\Models\CoachModel;
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
{
    $email = $this->request->getPost('email');
    $userModel = new LoginCoachModel();
    $user = $userModel->where('Email', $email)->first();

    if (!$user) {
        return redirect()->to('/coach-forgot-password')->with('error', 'Email not found.');
    }

    $token = bin2hex(random_bytes(50));
    $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

    $userModel->update($user['CoachID'], [
        'reset_token' => $token,
        'reset_token_expires' => $expiry
    ]);

    $resetLink = base_url("coach-reset-password/$token");

    $emailService = service('email');
    $emailService->setTo($email);
    $emailService->setFrom('taysonmiguelito125@gmail.com', 'IshowFitnessGYM');
    $emailService->setSubject('Password Reset Request');
    $emailService->setMessage("Hi, click the link to reset your coach password: <a href='$resetLink'>Reset Password</a>");

    if ($emailService->send()) {
        return redirect()->to('/coach-forgot-password')->with('success', 'Reset link sent. Check your email.');
    } else {
        return redirect()->to('/coach-forgot-password')->with('error', 'Failed to send email.');
    }
}

public function showResetForm($token)
{
    $userModel = new LoginCoachModel();
    $user = $userModel->where('reset_token', $token)->first();

    if (!$user || strtotime($user['reset_token_expires']) < time()) {
        return redirect()->to('/coach-forgot-password')->with('error', 'Invalid or expired reset link.');
    }

    return view('member_resetpassword/coachresetpass', ['token' => $token]);
}

public function resetPassword()
{
    $token = $this->request->getPost('token');
    $password = $this->request->getPost('password');

    $userModel = new LoginCoachModel();
    $user = $userModel->where('reset_token', $token)->first();

    if (!$user || strtotime($user['reset_token_expires']) < time()) {
        return redirect()->to('/coach-forgot-password')->with('error', 'Invalid or expired reset link.');
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $updateData = [
        'password_hash' => $hashedPassword,
        'reset_token' => null,
        'reset_token_expires' => null
    ];

    if ($userModel->update($user['CoachID'], $updateData)) {
        return redirect()->to('/coach-login')->with('success', 'Password reset successfully.');
    } else {
        return redirect()->to('/coach-forgot-password')->with('error', 'Something went wrong. Try again.');
    }
}


}



?>