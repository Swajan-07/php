<?php
session_start();
$servername = "localhost";
$username = "root"; // Change if needed
$password = ""; // Change if needed
$dbname = "user_database"; // Ensure database exists

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['user'] = $row['name']; // Store user session
                header("Location: dashboard.php"); // Redirect to dashboard
                exit();
            } else {
                $error = "Invalid email or password!";
            }
        } else {
            $error = "User not found!";
        }
    } else {
        $error = "All fields are required!";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            background: url('background.jpg') no-repeat center center/cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
        }

        .container {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            width: 350px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-login {
            background: #007bff;
            color: white;
        }

        .btn-login:hover {
            background: #0056b3;
        }

        .btn-register {
            background: #28a745;
            color: white;
        }

        .btn-register:hover {
            background: #218838;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
    <script>
        function validateForm() {
            let email = document.getElementById("email").value;
            let password = document.getElementById("password").value;
            let error = document.getElementById("error-message");

            if (email === "" || password === "") {
                error.textContent = "All fields are required!";
                return false;
            }

            let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                error.textContent = "Enter a valid email!";
                return false;
            }

            if (password.length < 6) {
                error.textContent = "Password must be at least 6 characters!";
                return false;
            }

            return true;
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Login</h2>
    <p class="error" id="error-message"><?= $error; ?></p>
    <form action="" method="post" onsubmit="return validateForm()">
        <input type="email" name="email" id="email" placeholder="Email" required>
        <input type="password" name="password" id="password" placeholder="Password" required>
        <button type="submit" class="btn btn-login">Login</button>
    </form>
</div>
<script>
    document.querySelector('.btn-login').addEventListener('click', function() {
        window.location.href = "index.html";
    });
</script>

</body>
</html>
