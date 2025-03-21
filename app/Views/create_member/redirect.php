<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        /* Base Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
            text-align: center;
            padding: 20px;
        }

        .container {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 90%; /* Makes it responsive */
            width: 400px;
            box-sizing: border-box;
        }

        h1 {
            font-size: 1.2em;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .emoji {
            font-size: 1.5em;
        }

        /* Button Styling */
        .btn {
            display: block;
            width: 100%;
            background-color: #d9534f; /* Red button */
            color: white;
            font-size: 16px;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease;
            margin-top: 10px;
        }

        .btn:hover {
            background-color: #c9302c; /* Darker red */
        }

        .register {
            display: block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }

        .register:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .container {
                width: 100%;
                padding: 20px;
            }

            h1 {
                font-size: 1.1em;
            }

            .btn {
                font-size: 14px;
                padding: 10px;
            }
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Woohoo! You're almost there! <span class="emoji">🎉</span><br> 
            We've sent a confirmation email to your Email!<br>
            Check your inbox for a message with a link to verify your address. 
        </h1>

        <a href="<?= base_url('/resendtoken') ?>" class="btn">Didn't Receive Email?</a>
        <a href="<?= base_url('/') ?>" class="register">Back to Homepage</a>
    </div>
</body>
</html>
