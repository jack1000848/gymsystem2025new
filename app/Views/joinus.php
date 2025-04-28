<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GYM Master - Login</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/Joinus.css') ?>">
    
</head>
<body></div><div id=container>
  Make 
  <div id=flip>
    <div><div>wOrK</div></div>
    <div><div>lifeStyle</div></div>
    <div><div>Everything</div></div>
  </div>
  AweSoMe!
</div>
    <div class="background">
        <div class="login-box">
            <div class="logo">
                <img src="" alt="">
                <h1>Welcome to</h1>
                <h1>Admin Login</h1>
            </div>
            <form action="<?= base_url('admin-login') ?>" method="post">
                <input type="text" name="username" id="username" placeholder="Username" required>
                 <input type="password" name="password" id="password" placeholder="Password" required>
                 <button type="submit">Login</button>
                <a href="<?= base_url('join-now') ?>" class="register">Signup</a>
                <a href="<?= base_url('member-login') ?>" class="register">Member Login</a>
              
                
            </form>
        </div>
    </div>
</body>
</html>
