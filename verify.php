<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Code</title>
    <style>
        body {
           
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
             background: linear-gradient(to right, #00c6ff, #0072ff);
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 300px;
        }

        h2 {
            color: white;
            margin-bottom: 20px;
        }

        label {
            display: white;
            font-size: 14px;
            margin-bottom: 10px;
            color: #333;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 2px solid #0072ff;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 16px;
            outline: none;
            transition: 0.3s;
        }

        input[type="text"]:focus {
            border-color: #00c6ff;
            box-shadow: 0 0 5px #00c6ff;
        }

        button {
            background-color: #0072ff;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #005ecb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Verification</h2>
        <form action="verifyprocess.php" method="post">
            <label>Enter the code sent to your WhatsApp:</label>
            <input type="text" name="code" required />
            <button type="submit">Verify</button>
        </form>
    </div>
</body>
</html>
