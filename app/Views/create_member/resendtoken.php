<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resend Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }
        .form-container label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 14px;
        }
        .form-container input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .form-container button {
            width: 100%;
            padding: 12px;
            background-color: #c0392b;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }
        .form-container button:hover {
            background-color: #c0392b;
        }

        /* Responsive Design */
        @media (max-width: 500px) {
            .form-container {
                width: 100%;
                padding: 15px;
            }
            .form-container input, .form-container button {
                font-size: 14px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>

    <div class="form-container">
        <form action="<?= base_url('/resend-verification') ?>" method="post">
            <label for="clients1Emailaddress">Enter your email to resend verification:</label>
            <input type="email" name="clients1Emailaddress" required>
            <button type="submit">Resend Verification Email</button>
        </form>
    </div>

</body>
</html>
