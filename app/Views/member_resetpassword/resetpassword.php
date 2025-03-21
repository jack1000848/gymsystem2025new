<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Password</title>
    <style>
        /* General body styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Form container */
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        /* Flash message */
        .alert {
            background-color: #d9534f;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        /* Headings */
        h2 {
            margin-bottom: 10px;
        }

        /* Input field */
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        /* Label */
        label {
            font-size: 14px;
            text-align: left;
            display: block;
        }

        /* Submit button */
        button {
            width: 100%;
            padding: 12px;
            background-color: #d9534f; /* Red button */
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #c9302c;
        }

        /* Responsive Design */
        @media (max-width: 768px) { /* Tablet */
            .container {
                width: 80%;
            }
        }

        @media (max-width: 480px) { /* Mobile */
            .container {
                width: 90%;
                padding: 15px;
            }

            h2 {
                font-size: 18px;
            }

            label {
                font-size: 12px;
            }

            input, button {
                font-size: 14px;
            }
        }

        @media (min-width: 1024px) { /* Laptop */
            .container {
                max-width: 450px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Enter Your New Password</h2>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <form action="<?= base_url('update-password') ?>" method="POST">
            <input type="hidden" name="token" value="<?= $token ?>">
            
            <label>New Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Update Password</button>
        </form>
    </div>

</body>
</html>
