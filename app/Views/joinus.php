<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GYM Master - Login</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/Joinus.css') ?>">
    <script>
        // JavaScript function to validate login credentials
        function validateLogin(event) {
            event.preventDefault(); // Prevent form submission
            var username = document.getElementById("username").value;
            var password = document.getElementById("password").value;

            // Check if username and password are both "admin"
            if (username === "admin" && password === "admin") {
                ///alert("Login successful!"); // Display success message
                window.location.href = "<?= base_url('admin') ?>"; // Redirect to dashboard
            } else {
                alert("Incorrect username or password! Please try again.");
            }
        }
    </script>
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
            <form onsubmit="validateLogin(event)">
                <input type="text" id="username" placeholder="Username" required>
                <input type="password" id="password" placeholder="Password" required>
                <button type="submit">Login</button>
                <a href="<?= base_url('join-now') ?>" class="register">Signup</a>
                <a href="<?= base_url('member-login') ?>" class="register">Member Login</a>
                <a href="<?= base_url('coach-login') ?>" class="register">Coach Login</a>
                
            </form>
        </div>
    </div>
</body>
</html>